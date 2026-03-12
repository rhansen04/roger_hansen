CREATE TABLE IF NOT EXISTS planning_daily_routines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    submission_id INT NOT NULL,
    day_of_week TINYINT NOT NULL COMMENT '1=Segunda, 2=Terca, 3=Quarta, 4=Quinta, 5=Sexta',
    time_slot VARCHAR(20) NOT NULL COMMENT 'ex: 08:00-08:30',
    activity_description TEXT NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (submission_id) REFERENCES planning_submissions(id) ON DELETE CASCADE,
    INDEX idx_submission_day (submission_id, day_of_week)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
