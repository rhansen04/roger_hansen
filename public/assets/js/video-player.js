/**
 * VideoTracker - Sistema de Tracking de Videos
 * Hansen Educacional
 */

class VideoTracker {
    constructor(lessonData, config) {
        this.lessonData = lessonData;
        this.config = config;
        this.player = null;
        this.videoId = this.extractVideoId(lessonData.videoUrl);
        this.currentProgress = null;
        this.trackingInterval = null;
        this.watchSessionId = null;
        this.sessionStartTime = null;
        this.isCompleted = false;
    }

    /**
     * Extrair ID do YouTube da URL
     */
    extractVideoId(url) {
        if (!url) return null;
        const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
        const match = url.match(regex);
        return match ? match[1] : null;
    }

    /**
     * Inicializar YouTube Player
     */
    initializePlayer() {
        if (!this.videoId) {
            console.error('ID do video nao encontrado na URL:', this.lessonData.videoUrl);
            document.getElementById('youtube-player').innerHTML =
                '<div class="alert alert-warning m-3">Video nao disponivel. Verifique a URL.</div>';
            return;
        }

        this.player = new YT.Player('youtube-player', {
            videoId: this.videoId,
            playerVars: {
                autoplay: 0,
                controls: 1,
                rel: 0,
                modestbranding: 1,
                fs: 1,
                playsinline: 1
            },
            events: {
                onReady: this.onPlayerReady.bind(this),
                onStateChange: this.onPlayerStateChange.bind(this)
            }
        });
    }

    /**
     * Player pronto - carregar progresso anterior
     */
    onPlayerReady(event) {
        this.loadPreviousProgress();
    }

    /**
     * Mudanca de estado do player
     */
    onPlayerStateChange(event) {
        if (event.data === YT.PlayerState.PLAYING) {
            this.startTracking();
            if (!this.watchSessionId) {
                this.startWatchSession();
            }
        } else if (event.data === YT.PlayerState.PAUSED) {
            this.stopTracking();
            this.saveProgress();
        } else if (event.data === YT.PlayerState.ENDED) {
            this.stopTracking();
            this.onVideoEnded();
        }
    }

    /**
     * Carregar progresso anterior para retomar
     */
    async loadPreviousProgress() {
        try {
            const response = await this.apiFetch(
                `/api/video-progress/${this.lessonData.enrollmentId}/${this.lessonData.id}`
            );
            const result = await response.json();

            if (result.success && result.data) {
                this.currentProgress = result.data;

                // Retomar de onde parou
                if (this.currentProgress.current_time > 0 && !this.currentProgress.is_completed) {
                    this.player.seekTo(this.currentProgress.current_time, true);
                }

                this.updateProgressBar(this.currentProgress.percentage_watched || 0);

                if (this.currentProgress.is_completed) {
                    this.isCompleted = true;
                    this.updateProgressBar(100);
                    this.markLessonComplete();
                }
            }
        } catch (error) {
            console.error('Erro ao carregar progresso:', error);
        }
    }

    /**
     * Iniciar tracking a cada 5 segundos
     */
    startTracking() {
        if (this.trackingInterval) {
            clearInterval(this.trackingInterval);
        }
        this.trackingInterval = setInterval(() => {
            this.saveProgress();
        }, this.config.trackingInterval);
    }

    /**
     * Parar tracking
     */
    stopTracking() {
        if (this.trackingInterval) {
            clearInterval(this.trackingInterval);
            this.trackingInterval = null;
        }
    }

    /**
     * Salvar progresso (chamado a cada 5s)
     */
    async saveProgress() {
        if (!this.player || typeof this.player.getCurrentTime !== 'function') return;

        const currentTime = this.player.getCurrentTime();
        const duration = this.player.getDuration();
        if (!duration || duration <= 0) return;

        const percentage = (currentTime / duration) * 100;

        try {
            const response = await this.apiFetch('/api/video-progress', {
                method: 'POST',
                body: JSON.stringify({
                    enrollment_id: this.lessonData.enrollmentId,
                    lesson_id: this.lessonData.id,
                    current_time: currentTime,
                    total_duration: duration,
                    percentage_watched: percentage
                })
            });

            const result = await response.json();

            if (result.success) {
                this.currentProgress = result.data;
                this.updateProgressBar(percentage);

                if (percentage >= this.config.completionThreshold && !this.isCompleted) {
                    this.isCompleted = true;
                    this.onVideoCompleted();
                }
            }
        } catch (error) {
            console.error('Erro ao salvar progresso:', error);
        }
    }

    /**
     * Atualizar barra de progresso visual
     */
    updateProgressBar(percentage) {
        const bar = document.getElementById('video-progress-bar');
        if (!bar) return;

        percentage = Math.min(100, Math.max(0, percentage));
        bar.style.width = percentage + '%';
        bar.textContent = Math.round(percentage) + '%';
        bar.setAttribute('aria-valuenow', percentage);

        if (percentage >= this.config.completionThreshold) {
            bar.classList.remove('bg-primary-hansen');
            bar.classList.add('bg-success');
        }
    }

    /**
     * Video completado (>= 97%)
     */
    onVideoCompleted() {
        this.updateProgressBar(100);
        this.markLessonComplete();
        this.updateCourseProgress();
        this.showNotification('Licao concluida!', 'success');
    }

    /**
     * Video terminou (chegou ao fim)
     */
    onVideoEnded() {
        this.saveProgress();
        this.endWatchSession();
        this.updateCourseProgress();
    }

    /**
     * Marcar visualmente a licao como completa na sidebar
     */
    markLessonComplete() {
        const currentItem = document.querySelector(`.lesson-item[data-lesson-id="${this.lessonData.id}"]`);
        if (currentItem) {
            const icon = currentItem.querySelector('.lesson-status');
            if (icon) {
                icon.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
            }
        }
    }

    /**
     * Iniciar sessao de visualizacao
     */
    async startWatchSession() {
        if (!this.currentProgress || !this.currentProgress.id) return;

        this.sessionStartTime = new Date();

        try {
            const response = await this.apiFetch('/api/video-start-session', {
                method: 'POST',
                body: JSON.stringify({
                    video_progress_id: this.currentProgress.id,
                    device_info: navigator.userAgent
                })
            });

            const result = await response.json();
            if (result.success) {
                this.watchSessionId = result.data.watch_log_id;
            }
        } catch (error) {
            console.error('Erro ao iniciar sessao:', error);
        }
    }

    /**
     * Finalizar sessao de visualizacao
     */
    async endWatchSession() {
        if (!this.watchSessionId || !this.sessionStartTime) return;

        const sessionDuration = Math.floor((new Date() - this.sessionStartTime) / 1000);
        const currentTime = this.player ? this.player.getCurrentTime() : 0;
        const duration = this.player ? this.player.getDuration() : 1;
        const percentage = (currentTime / duration) * 100;

        try {
            await this.apiFetch('/api/video-end-session', {
                method: 'POST',
                body: JSON.stringify({
                    watch_log_id: this.watchSessionId,
                    session_duration: sessionDuration,
                    percentage_after: percentage,
                    completed: percentage >= this.config.completionThreshold
                })
            });
        } catch (error) {
            console.error('Erro ao finalizar sessao:', error);
        }

        this.watchSessionId = null;
        this.sessionStartTime = null;
    }

    /**
     * Atualizar progresso do curso na sidebar
     */
    async updateCourseProgress() {
        try {
            const response = await this.apiFetch(
                `/api/course-progress/${this.lessonData.enrollmentId}`,
                { method: 'POST' }
            );

            const result = await response.json();

            if (result.success) {
                const bar = document.getElementById('course-progress-bar');
                if (bar) {
                    const pct = result.data.progress_percentage || 0;
                    bar.style.width = pct + '%';
                    bar.textContent = Math.round(pct) + '%';
                }

                const count = document.getElementById('course-videos-count');
                if (count) {
                    count.textContent = `${result.data.completed_videos}/${result.data.total_videos} videos`;
                }

                if (result.data.is_completed) {
                    this.showNotification('Parabens! Voce completou o curso!', 'success');
                }
            }
        } catch (error) {
            console.error('Erro ao atualizar progresso do curso:', error);
        }
    }

    /**
     * Fetch com CSRF token
     */
    apiFetch(url, options = {}) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        options.headers = {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken,
            ...(options.headers || {})
        };
        return fetch(url, options);
    }

    /**
     * Mostrar notificacao toast
     */
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed shadow`;
        notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; max-width: 350px;';
        notification.innerHTML = `
            <strong>${type === 'success' ? '<i class="fas fa-check-circle me-1"></i>' : ''}</strong>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 150);
        }, 4000);
    }
}

// Variavel global para acesso no beforeunload
let tracker = null;

// Callback da YouTube Iframe API
function onYouTubeIframeAPIReady() {
    if (typeof lessonData !== 'undefined' && typeof trackingConfig !== 'undefined') {
        tracker = new VideoTracker(lessonData, trackingConfig);
        tracker.initializePlayer();
    }
}

// Salvar progresso antes de fechar a pagina (sendBeacon)
window.addEventListener('beforeunload', () => {
    if (!tracker || !tracker.player || typeof tracker.player.getCurrentTime !== 'function') return;

    const currentTime = tracker.player.getCurrentTime();
    const duration = tracker.player.getDuration();
    if (!duration || duration <= 0) return;

    const percentage = (currentTime / duration) * 100;

    // Salvar progresso via sendBeacon (garantido pelo browser)
    const progressData = JSON.stringify({
        enrollment_id: tracker.lessonData.enrollmentId,
        lesson_id: tracker.lessonData.id,
        current_time: currentTime,
        total_duration: duration,
        percentage_watched: percentage
    });
    navigator.sendBeacon('/api/video-progress', new Blob([progressData], { type: 'application/json' }));

    // Finalizar sessao via sendBeacon
    if (tracker.watchSessionId && tracker.sessionStartTime) {
        const sessionData = JSON.stringify({
            watch_log_id: tracker.watchSessionId,
            session_duration: Math.floor((new Date() - tracker.sessionStartTime) / 1000),
            percentage_after: percentage,
            completed: percentage >= tracker.config.completionThreshold
        });
        navigator.sendBeacon('/api/video-end-session', new Blob([sessionData], { type: 'application/json' }));
    }
});
