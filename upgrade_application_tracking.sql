ALTER TABLE internships
    MODIFY status ENUM('Applied','Shortlisted','Interview','Offer Received','Accepted','Selected','Rejected','Withdrawn','In Progress','Completed') NOT NULL DEFAULT 'Applied',
    ADD COLUMN interview_date DATE DEFAULT NULL AFTER deadline,
    ADD COLUMN interview_notes TEXT DEFAULT NULL AFTER interview_date,
    ADD COLUMN offer_received_date DATE DEFAULT NULL AFTER interview_notes,
    ADD COLUMN offer_deadline DATE DEFAULT NULL AFTER offer_received_date,
    ADD COLUMN stipend VARCHAR(100) DEFAULT NULL AFTER offer_deadline,
    ADD COLUMN internship_start_date DATE DEFAULT NULL AFTER stipend,
    ADD COLUMN offer_notes TEXT DEFAULT NULL AFTER internship_start_date,
    ADD INDEX idx_student_interview (student_id, interview_date);
