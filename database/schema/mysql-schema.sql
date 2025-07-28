/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `academic_calendars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `academic_calendars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `title` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academic_calendars_school_id_foreign` (`school_id`),
  KEY `academic_calendars_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `academic_calendars_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `academic_calendars_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `addon_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addon_subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint(20) unsigned NOT NULL,
  `feature_id` bigint(20) unsigned NOT NULL,
  `price` double(8,4) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 => Discontinue next billing, 1 => Continue',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addon_subscriptions_school_id_foreign` (`school_id`),
  KEY `addon_subscriptions_feature_id_foreign` (`feature_id`),
  CONSTRAINT `addon_subscriptions_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE,
  CONSTRAINT `addon_subscriptions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(8,4) NOT NULL COMMENT 'Daily price',
  `feature_id` bigint(20) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 => Inactive, 1 => Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `addons_feature_id_unique` (`feature_id`),
  CONSTRAINT `addons_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `announcement_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcement_classes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `announcement_id` bigint(20) unsigned DEFAULT NULL,
  `class_section_id` bigint(20) unsigned DEFAULT NULL,
  `class_subject_id` bigint(20) unsigned DEFAULT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_columns` (`announcement_id`,`class_section_id`,`school_id`),
  KEY `announcement_classes_school_id_foreign` (`school_id`),
  KEY `announcement_classes_announcement_id_index` (`announcement_id`),
  KEY `announcement_classes_class_section_id_index` (`class_section_id`),
  KEY `announcement_classes_class_subject_id_foreign` (`class_subject_id`),
  CONSTRAINT `announcement_classes_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `announcement_classes_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `announcement_classes_class_subject_id_foreign` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`id`),
  CONSTRAINT `announcement_classes_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_school_id_foreign` (`school_id`),
  KEY `announcements_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `announcements_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `announcements_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `assignment_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assignment_submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `assignment_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `points` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = Pending/In Review , 1 = Accepted , 2 = Rejected , 3 = Resubmitted',
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assignment_submissions_assignment_id_foreign` (`assignment_id`),
  KEY `assignment_submissions_school_id_foreign` (`school_id`),
  KEY `assignment_submissions_student_id_foreign` (`student_id`),
  KEY `assignment_submissions_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `assignment_submissions_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assignment_submissions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assignment_submissions_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`),
  CONSTRAINT `assignment_submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assignments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_section_id` bigint(20) unsigned NOT NULL,
  `class_subject_id` bigint(20) unsigned NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` datetime NOT NULL,
  `points` int(11) DEFAULT NULL,
  `resubmission` tinyint(1) NOT NULL DEFAULT '0',
  `extra_days_for_resubmission` int(11) DEFAULT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL COMMENT 'teacher_user_id',
  `edited_by` bigint(20) unsigned DEFAULT NULL COMMENT 'teacher_user_id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assignments_school_id_foreign` (`school_id`),
  KEY `assignments_class_section_id_foreign` (`class_section_id`),
  KEY `assignments_class_subject_id_foreign` (`class_subject_id`),
  KEY `assignments_session_year_id_foreign` (`session_year_id`),
  KEY `assignments_created_by_foreign` (`created_by`),
  KEY `assignments_edited_by_foreign` (`edited_by`),
  CONSTRAINT `assignments_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `assignments_class_subject_id_foreign` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`id`),
  CONSTRAINT `assignments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `assignments_edited_by_foreign` FOREIGN KEY (`edited_by`) REFERENCES `users` (`id`),
  CONSTRAINT `assignments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assignments_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_section_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `session_year_id` bigint(20) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '0=Absent, 1=Present',
  `date` date NOT NULL,
  `remark` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_school_id_foreign` (`school_id`),
  KEY `attendances_class_section_id_foreign` (`class_section_id`),
  KEY `attendances_student_id_foreign` (`student_id`),
  KEY `attendances_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `attendances_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `attendances_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`),
  CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_school_id_foreign` (`school_id`),
  CONSTRAINT `categories_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `class_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint(20) unsigned NOT NULL,
  `section_id` bigint(20) unsigned NOT NULL,
  `medium_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id` (`class_id`,`section_id`,`medium_id`),
  KEY `class_sections_school_id_foreign` (`school_id`),
  KEY `class_sections_section_id_foreign` (`section_id`),
  KEY `class_sections_medium_id_foreign` (`medium_id`),
  CONSTRAINT `class_sections_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  CONSTRAINT `class_sections_medium_id_foreign` FOREIGN KEY (`medium_id`) REFERENCES `mediums` (`id`),
  CONSTRAINT `class_sections_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_sections_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `class_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_subjects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint(20) unsigned NOT NULL,
  `subject_id` bigint(20) unsigned NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Compulsory / Elective',
  `elective_subject_group_id` bigint(20) unsigned DEFAULT NULL COMMENT 'if type=Elective',
  `semester_id` bigint(20) unsigned DEFAULT NULL,
  `virtual_semester_id` int(11) GENERATED ALWAYS AS ((case when (`semester_id` is not null) then `semester_id` else 0 end)) VIRTUAL,
  `school_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_ids` (`class_id`,`subject_id`,`virtual_semester_id`),
  KEY `class_subjects_elective_subject_group_id_foreign` (`elective_subject_group_id`),
  KEY `class_subjects_school_id_foreign` (`school_id`),
  KEY `class_subjects_subject_id_foreign` (`subject_id`),
  KEY `class_subjects_semester_id_foreign` (`semester_id`),
  CONSTRAINT `class_subjects_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  CONSTRAINT `class_subjects_elective_subject_group_id_foreign` FOREIGN KEY (`elective_subject_group_id`) REFERENCES `elective_subject_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_subjects_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_subjects_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`),
  CONSTRAINT `class_subjects_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `class_teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_teachers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_section_id` bigint(20) unsigned NOT NULL,
  `teacher_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id` (`class_section_id`,`teacher_id`),
  KEY `class_teachers_school_id_foreign` (`school_id`),
  KEY `class_teachers_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `class_teachers_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `class_teachers_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_teachers_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `include_semesters` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - no 1 - yes',
  `medium_id` bigint(20) unsigned NOT NULL,
  `shift_id` bigint(20) unsigned DEFAULT NULL,
  `stream_id` bigint(20) unsigned DEFAULT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `classes_school_id_foreign` (`school_id`),
  KEY `classes_medium_id_foreign` (`medium_id`),
  KEY `classes_shift_id_foreign` (`shift_id`),
  KEY `classes_stream_id_foreign` (`stream_id`),
  CONSTRAINT `classes_medium_id_foreign` FOREIGN KEY (`medium_id`) REFERENCES `mediums` (`id`),
  CONSTRAINT `classes_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `classes_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`),
  CONSTRAINT `classes_stream_id_foreign` FOREIGN KEY (`stream_id`) REFERENCES `streams` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `compulsory_fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compulsory_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `payment_transaction_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('Full Payment','Installment Payment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `installment_id` bigint(20) unsigned DEFAULT NULL,
  `mode` enum('Cash','Cheque','Online') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cheque_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(8,2) NOT NULL,
  `due_charges` double(8,2) DEFAULT NULL,
  `fees_paid_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('Success','Pending','Failed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compulsory_fees_student_id_foreign` (`student_id`),
  KEY `compulsory_fees_payment_transaction_id_foreign` (`payment_transaction_id`),
  KEY `compulsory_fees_installment_id_foreign` (`installment_id`),
  KEY `compulsory_fees_fees_paid_id_foreign` (`fees_paid_id`),
  KEY `compulsory_fees_school_id_foreign` (`school_id`),
  CONSTRAINT `compulsory_fees_fees_paid_id_foreign` FOREIGN KEY (`fees_paid_id`) REFERENCES `fees_paids` (`id`) ON DELETE CASCADE,
  CONSTRAINT `compulsory_fees_installment_id_foreign` FOREIGN KEY (`installment_id`) REFERENCES `fees_installments` (`id`),
  CONSTRAINT `compulsory_fees_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `compulsory_fees_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `compulsory_fees_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `elective_subject_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `elective_subject_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `total_subjects` int(11) NOT NULL,
  `total_selectable_subjects` int(11) NOT NULL,
  `class_id` bigint(20) unsigned NOT NULL,
  `semester_id` bigint(20) unsigned DEFAULT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `elective_subject_groups_school_id_foreign` (`school_id`),
  KEY `elective_subject_groups_class_id_foreign` (`class_id`),
  KEY `elective_subject_groups_semester_id_foreign` (`semester_id`),
  CONSTRAINT `elective_subject_groups_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  CONSTRAINT `elective_subject_groups_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `elective_subject_groups_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `exam_marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_marks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `exam_timetable_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `class_subject_id` bigint(20) unsigned NOT NULL,
  `obtained_marks` double(8,2) NOT NULL,
  `teacher_review` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passing_status` tinyint(1) NOT NULL COMMENT '1=Pass, 0=Fail',
  `session_year_id` bigint(20) unsigned NOT NULL,
  `grade` tinytext COLLATE utf8mb4_unicode_ci,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_marks_school_id_foreign` (`school_id`),
  KEY `exam_marks_exam_timetable_id_foreign` (`exam_timetable_id`),
  KEY `exam_marks_student_id_foreign` (`student_id`),
  KEY `exam_marks_class_subject_id_foreign` (`class_subject_id`),
  KEY `exam_marks_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `exam_marks_class_subject_id_foreign` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`id`),
  CONSTRAINT `exam_marks_exam_timetable_id_foreign` FOREIGN KEY (`exam_timetable_id`) REFERENCES `exam_timetables` (`id`),
  CONSTRAINT `exam_marks_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_marks_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`),
  CONSTRAINT `exam_marks_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `exam_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_results` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `exam_id` bigint(20) unsigned NOT NULL,
  `class_section_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `total_marks` int(11) NOT NULL,
  `obtained_marks` double(8,2) NOT NULL,
  `percentage` double(8,2) NOT NULL,
  `grade` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_results_school_id_foreign` (`school_id`),
  KEY `exam_results_exam_id_foreign` (`exam_id`),
  KEY `exam_results_class_section_id_foreign` (`class_section_id`),
  KEY `exam_results_student_id_foreign` (`student_id`),
  KEY `exam_results_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `exam_results_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `exam_results_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`),
  CONSTRAINT `exam_results_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_results_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`),
  CONSTRAINT `exam_results_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `exam_timetables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_timetables` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `exam_id` bigint(20) unsigned NOT NULL,
  `class_subject_id` bigint(20) unsigned NOT NULL,
  `total_marks` double(8,2) NOT NULL,
  `passing_marks` double(8,2) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_timetables_exam_id_foreign` (`exam_id`),
  KEY `exam_timetables_school_id_foreign` (`school_id`),
  KEY `exam_timetables_class_subject_id_foreign` (`class_subject_id`),
  KEY `exam_timetables_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `exam_timetables_class_subject_id_foreign` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`id`),
  CONSTRAINT `exam_timetables_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_timetables_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_timetables_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_id` bigint(20) unsigned NOT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `publish` tinyint(4) NOT NULL DEFAULT '0',
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exams_school_id_foreign` (`school_id`),
  KEY `exams_class_id_foreign` (`class_id`),
  KEY `exams_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `exams_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  CONSTRAINT `exams_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exams_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `expense_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expense_categories_school_id_foreign` (`school_id`),
  CONSTRAINT `expense_categories_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `ref_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_id` bigint(20) unsigned DEFAULT NULL,
  `basic_salary` bigint(20) NOT NULL DEFAULT '0',
  `paid_leaves` double(8,2) NOT NULL DEFAULT '0.00',
  `month` bigint(20) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `title` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(8,2) NOT NULL,
  `date` date NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `salary_unique_records` (`staff_id`,`month`,`year`),
  KEY `expenses_school_id_foreign` (`school_id`),
  KEY `expenses_category_id_foreign` (`category_id`),
  KEY `expenses_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`),
  CONSTRAINT `expenses_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expenses_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`),
  CONSTRAINT `expenses_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `extra_student_datas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extra_student_datas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `form_field_id` bigint(20) unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `extra_student_datas_form_field_id_foreign` (`form_field_id`),
  KEY `extra_student_datas_school_id_foreign` (`school_id`),
  KEY `extra_student_datas_student_id_foreign` (`student_id`),
  CONSTRAINT `extra_student_datas_form_field_id_foreign` FOREIGN KEY (`form_field_id`) REFERENCES `form_fields` (`id`) ON DELETE CASCADE,
  CONSTRAINT `extra_student_datas_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `extra_student_datas_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 => No, 1 => Yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `due_date` date NOT NULL,
  `due_charges` double(8,2) NOT NULL COMMENT 'in percentage (%)',
  `class_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fees_class_id_foreign` (`class_id`),
  KEY `fees_school_id_foreign` (`school_id`),
  KEY `fees_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `fees_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  CONSTRAINT `fees_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fees_advance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_advance` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `compulsory_fee_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `parent_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fees_advance_compulsory_fee_id_foreign` (`compulsory_fee_id`),
  KEY `fees_advance_student_id_foreign` (`student_id`),
  KEY `fees_advance_parent_id_foreign` (`parent_id`),
  CONSTRAINT `fees_advance_compulsory_fee_id_foreign` FOREIGN KEY (`compulsory_fee_id`) REFERENCES `compulsory_fees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_advance_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_advance_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fees_class_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_class_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint(20) unsigned NOT NULL,
  `fees_id` bigint(20) unsigned NOT NULL,
  `fees_type_id` bigint(20) unsigned NOT NULL,
  `amount` double(8,2) NOT NULL,
  `optional` tinyint(1) NOT NULL COMMENT '0 - No, 1 - Yes',
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_ids` (`class_id`,`fees_type_id`,`school_id`,`fees_id`),
  KEY `fees_class_types_fees_id_foreign` (`fees_id`),
  KEY `fees_class_types_fees_type_id_foreign` (`fees_type_id`),
  KEY `fees_class_types_school_id_foreign` (`school_id`),
  CONSTRAINT `fees_class_types_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_class_types_fees_id_foreign` FOREIGN KEY (`fees_id`) REFERENCES `fees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_class_types_fees_type_id_foreign` FOREIGN KEY (`fees_type_id`) REFERENCES `fees_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_class_types_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fees_installments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_installments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `due_date` date NOT NULL,
  `due_charges` int(11) NOT NULL COMMENT 'in percentage (%)',
  `fees_id` bigint(20) unsigned NOT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fees_type_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fees_installments_fees_id_foreign` (`fees_id`),
  KEY `fees_installments_session_year_id_foreign` (`session_year_id`),
  KEY `fees_installments_school_id_foreign` (`school_id`),
  KEY `fees_installments_fees_type_id_foreign` (`fees_type_id`),
  CONSTRAINT `fees_installments_fees_id_foreign` FOREIGN KEY (`fees_id`) REFERENCES `fees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_installments_fees_type_id_foreign` FOREIGN KEY (`fees_type_id`) REFERENCES `fees_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_installments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_installments_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fees_paids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_paids` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fees_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `is_fully_paid` tinyint(1) NOT NULL COMMENT '0 - No, 1 - Yes',
  `is_used_installment` tinyint(1) NOT NULL COMMENT '0 - No, 1 - Yes',
  `amount` double(8,2) NOT NULL,
  `date` date NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_ids` (`student_id`,`fees_id`,`school_id`),
  KEY `fees_paids_fees_id_foreign` (`fees_id`),
  KEY `fees_paids_school_id_foreign` (`school_id`),
  CONSTRAINT `fees_paids_fees_id_foreign` FOREIGN KEY (`fees_id`) REFERENCES `fees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_paids_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_paids_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fees_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fees_types_school_id_foreign` (`school_id`),
  CONSTRAINT `fees_types_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `modal_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modal_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_thumbnail` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinytext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1 = File Upload, 2 = Youtube Link, 3 = Video Upload, 4 = Other Link',
  `file_url` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `files_modal_type_modal_id_index` (`modal_type`,`modal_id`),
  KEY `files_school_id_foreign` (`school_id`),
  CONSTRAINT `files_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `form_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_fields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'text,number,textarea,dropdown,checkbox,radio,fileupload',
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `default_values` text COLLATE utf8mb4_unicode_ci COMMENT 'values of radio,checkbox,dropdown,etc',
  `other` text COLLATE utf8mb4_unicode_ci COMMENT 'extra HTML attributes',
  `school_id` bigint(20) unsigned NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`school_id`),
  KEY `form_fields_school_id_foreign` (`school_id`),
  CONSTRAINT `form_fields_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grades` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `starting_range` double(8,2) NOT NULL,
  `ending_range` double(8,2) NOT NULL,
  `grade` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grades_school_id_foreign` (`school_id`),
  CONSTRAINT `grades_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `guidances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guidances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `holidays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `holidays` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `holidays_school_id_foreign` (`school_id`),
  CONSTRAINT `holidays_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=>active',
  `is_rtl` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `languages_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `leave_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `leave_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leave_details_leave_id_foreign` (`leave_id`),
  KEY `leave_details_school_id_foreign` (`school_id`),
  CONSTRAINT `leave_details_leave_id_foreign` FOREIGN KEY (`leave_id`) REFERENCES `leaves` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leave_details_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `leave_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_masters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `leaves` double(8,2) NOT NULL COMMENT 'Leaves per month',
  `holiday` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leave_masters_session_year_id_foreign` (`session_year_id`),
  KEY `leave_masters_school_id_foreign` (`school_id`),
  CONSTRAINT `leave_masters_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leave_masters_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leaves` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 => Pending, 1 => Approved, 2 => Rejected',
  `school_id` bigint(20) unsigned NOT NULL,
  `leave_master_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leaves_user_id_foreign` (`user_id`),
  KEY `leaves_school_id_foreign` (`school_id`),
  KEY `leaves_leave_master_id_foreign` (`leave_master_id`),
  CONSTRAINT `leaves_leave_master_id_foreign` FOREIGN KEY (`leave_master_id`) REFERENCES `leave_masters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leaves_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leaves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `lesson_topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson_topics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` bigint(20) unsigned NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_topics_lesson_id_foreign` (`lesson_id`),
  KEY `lesson_topics_school_id_foreign` (`school_id`),
  CONSTRAINT `lesson_topics_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lesson_topics_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lessons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_section_id` bigint(20) unsigned NOT NULL,
  `class_subject_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lessons_school_id_foreign` (`school_id`),
  KEY `lessons_class_section_id_foreign` (`class_section_id`),
  KEY `lessons_class_subject_id_foreign` (`class_subject_id`),
  CONSTRAINT `lessons_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `lessons_class_subject_id_foreign` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`id`),
  CONSTRAINT `lessons_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `mediums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mediums` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mediums_school_id_foreign` (`school_id`),
  CONSTRAINT `mediums_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `online_exam_question_choices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_exam_question_choices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `online_exam_id` bigint(20) unsigned NOT NULL,
  `question_id` bigint(20) unsigned NOT NULL,
  `marks` int(11) DEFAULT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `online_exam_question_choices_school_id_foreign` (`school_id`),
  KEY `online_exam_question_choices_online_exam_id_foreign` (`online_exam_id`),
  KEY `online_exam_question_choices_question_id_foreign` (`question_id`),
  CONSTRAINT `online_exam_question_choices_online_exam_id_foreign` FOREIGN KEY (`online_exam_id`) REFERENCES `online_exams` (`id`),
  CONSTRAINT `online_exam_question_choices_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `online_exam_questions` (`id`),
  CONSTRAINT `online_exam_question_choices_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `online_exam_question_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_exam_question_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) unsigned NOT NULL,
  `option` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_answer` tinyint(4) NOT NULL COMMENT '1 - yes, 0 - no',
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `online_exam_question_options_question_id_foreign` (`question_id`),
  KEY `online_exam_question_options_school_id_foreign` (`school_id`),
  CONSTRAINT `online_exam_question_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `online_exam_questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `online_exam_question_options_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `online_exam_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_exam_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_section_id` bigint(20) unsigned NOT NULL,
  `class_subject_id` bigint(20) unsigned NOT NULL,
  `question` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `last_edited_by` bigint(20) unsigned NOT NULL COMMENT 'teacher_user_id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `online_exam_questions_school_id_foreign` (`school_id`),
  KEY `online_exam_questions_class_section_id_foreign` (`class_section_id`),
  KEY `online_exam_questions_class_subject_id_foreign` (`class_subject_id`),
  KEY `online_exam_questions_last_edited_by_foreign` (`last_edited_by`),
  CONSTRAINT `online_exam_questions_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `online_exam_questions_class_subject_id_foreign` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`id`),
  CONSTRAINT `online_exam_questions_last_edited_by_foreign` FOREIGN KEY (`last_edited_by`) REFERENCES `users` (`id`),
  CONSTRAINT `online_exam_questions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `online_exam_student_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_exam_student_answers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `online_exam_id` bigint(20) unsigned NOT NULL,
  `question_id` bigint(20) unsigned NOT NULL,
  `option_id` bigint(20) unsigned NOT NULL,
  `submitted_date` date NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `online_exam_student_answers_school_id_foreign` (`school_id`),
  KEY `online_exam_student_answers_student_id_foreign` (`student_id`),
  KEY `online_exam_student_answers_online_exam_id_foreign` (`online_exam_id`),
  KEY `online_exam_student_answers_question_id_foreign` (`question_id`),
  KEY `online_exam_student_answers_option_id_foreign` (`option_id`),
  CONSTRAINT `online_exam_student_answers_online_exam_id_foreign` FOREIGN KEY (`online_exam_id`) REFERENCES `online_exams` (`id`),
  CONSTRAINT `online_exam_student_answers_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `online_exam_question_options` (`id`),
  CONSTRAINT `online_exam_student_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `online_exam_question_choices` (`id`),
  CONSTRAINT `online_exam_student_answers_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `online_exam_student_answers_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `online_exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_exams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_section_id` bigint(20) unsigned NOT NULL,
  `class_subject_id` bigint(20) unsigned NOT NULL,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_key` bigint(20) NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'in minutes',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `online_exams_school_id_foreign` (`school_id`),
  KEY `online_exams_class_section_id_foreign` (`class_section_id`),
  KEY `online_exams_class_subject_id_foreign` (`class_subject_id`),
  KEY `online_exams_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `online_exams_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `online_exams_class_subject_id_foreign` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`id`),
  CONSTRAINT `online_exams_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `online_exams_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `optional_fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `optional_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `class_id` bigint(20) unsigned NOT NULL,
  `payment_transaction_id` bigint(20) unsigned DEFAULT NULL,
  `fees_class_id` bigint(20) unsigned DEFAULT NULL,
  `mode` enum('Cash','Cheque','Online') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cheque_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(8,2) NOT NULL,
  `fees_paid_id` bigint(20) unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `status` enum('Success','Pending','Failed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `optional_fees_student_id_foreign` (`student_id`),
  KEY `optional_fees_class_id_foreign` (`class_id`),
  KEY `optional_fees_payment_transaction_id_foreign` (`payment_transaction_id`),
  KEY `optional_fees_fees_class_id_foreign` (`fees_class_id`),
  KEY `optional_fees_fees_paid_id_foreign` (`fees_paid_id`),
  KEY `optional_fees_school_id_foreign` (`school_id`),
  CONSTRAINT `optional_fees_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `optional_fees_fees_class_id_foreign` FOREIGN KEY (`fees_class_id`) REFERENCES `fees_class_types` (`id`),
  CONSTRAINT `optional_fees_fees_paid_id_foreign` FOREIGN KEY (`fees_paid_id`) REFERENCES `fees_paids` (`id`) ON DELETE CASCADE,
  CONSTRAINT `optional_fees_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `optional_fees_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `optional_fees_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `package_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `package_features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `feature_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`package_id`,`feature_id`),
  KEY `package_features_package_id_index` (`package_id`),
  KEY `package_features_feature_id_index` (`feature_id`),
  CONSTRAINT `package_features_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`),
  CONSTRAINT `package_features_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tagline` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_charge` double(8,4) NOT NULL DEFAULT '0.0000',
  `staff_charge` double(8,4) NOT NULL DEFAULT '0.0000',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 => Unpublished, 1 => Published',
  `is_trial` int(11) NOT NULL DEFAULT '0',
  `highlight` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 => No, 1 => Yes',
  `rank` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payment_configurations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_configurations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `webhook_secret_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 - Disabled, 1 - Enabled',
  `school_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_configurations_school_id_foreign` (`school_id`),
  CONSTRAINT `payment_configurations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payment_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `amount` double(8,2) NOT NULL,
  `payment_gateway` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'order_id / payment_intent_id',
  `payment_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_signature` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` enum('failed','succeed','pending') COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_transactions_user_id_foreign` (`user_id`),
  KEY `payment_transactions_school_id_foreign` (`school_id`),
  CONSTRAINT `payment_transactions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `promote_students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promote_students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `class_section_id` bigint(20) unsigned NOT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `result` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=>Pass,0=>fail',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=>continue,0=>leave',
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_columns` (`student_id`,`class_section_id`,`session_year_id`),
  KEY `promote_students_school_id_foreign` (`school_id`),
  KEY `promote_students_class_section_id_foreign` (`class_section_id`),
  KEY `promote_students_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `promote_students_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `promote_students_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `promote_students_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`),
  CONSTRAINT `promote_students_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint(20) unsigned DEFAULT NULL,
  `custom_role` tinyint(1) NOT NULL DEFAULT '1',
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_school_id_unique` (`name`,`guard_name`,`school_id`),
  KEY `roles_school_id_foreign` (`school_id`),
  CONSTRAINT `roles_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `school_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'datatype like string , file etc',
  `school_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `school_settings_name_school_id_unique` (`name`,`school_id`),
  KEY `school_settings_school_id_foreign` (`school_id`),
  CONSTRAINT `school_settings_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schools` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `support_phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `support_email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagline` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL COMMENT 'user_id',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 => Deactivate, 1 => Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schools_admin_id_foreign` (`admin_id`),
  CONSTRAINT `schools_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sections_school_id_foreign` (`school_id`),
  CONSTRAINT `sections_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `semesters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `semesters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_month` tinyint(4) NOT NULL,
  `end_month` tinyint(4) NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `semesters_school_id_foreign` (`school_id`),
  CONSTRAINT `semesters_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `session_years`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session_years` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default` tinyint(4) NOT NULL DEFAULT '0',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_years_name_school_id_unique` (`name`,`school_id`),
  KEY `session_years_school_id_foreign` (`school_id`),
  CONSTRAINT `session_years_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shifts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shifts_school_id_foreign` (`school_id`),
  CONSTRAINT `shifts_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sliders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sliders_school_id_foreign` (`school_id`),
  CONSTRAINT `sliders_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `staff_support_schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_support_schools` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_school` (`user_id`,`school_id`),
  KEY `staff_support_schools_school_id_foreign` (`school_id`),
  CONSTRAINT `staff_support_schools_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `staff_support_schools_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `staffs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staffs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `qualification` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staffs_user_id_foreign` (`user_id`),
  CONSTRAINT `staffs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `streams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `streams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `streams_school_id_foreign` (`school_id`),
  CONSTRAINT `streams_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `student_online_exam_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_online_exam_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `online_exam_id` bigint(20) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1 - in progress 2 - completed',
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_online_exam_statuses_school_id_foreign` (`school_id`),
  KEY `student_online_exam_statuses_student_id_foreign` (`student_id`),
  KEY `student_online_exam_statuses_online_exam_id_foreign` (`online_exam_id`),
  CONSTRAINT `student_online_exam_statuses_online_exam_id_foreign` FOREIGN KEY (`online_exam_id`) REFERENCES `online_exams` (`id`),
  CONSTRAINT `student_online_exam_statuses_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_online_exam_statuses_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `student_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_subjects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `class_subject_id` bigint(20) unsigned NOT NULL,
  `class_section_id` bigint(20) unsigned NOT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_subjects_school_id_foreign` (`school_id`),
  KEY `student_subjects_student_id_foreign` (`student_id`),
  KEY `student_subjects_class_subject_id_foreign` (`class_subject_id`),
  KEY `student_subjects_class_section_id_foreign` (`class_section_id`),
  KEY `student_subjects_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `student_subjects_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `student_subjects_class_subject_id_foreign` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`id`),
  CONSTRAINT `student_subjects_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_subjects_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`),
  CONSTRAINT `student_subjects_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `class_section_id` bigint(20) unsigned NOT NULL,
  `admission_no` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roll_number` int(11) DEFAULT NULL,
  `admission_date` date NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `guardian_id` bigint(20) unsigned NOT NULL,
  `session_year_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `students_school_id_foreign` (`school_id`),
  KEY `students_user_id_foreign` (`user_id`),
  KEY `students_class_section_id_foreign` (`class_section_id`),
  KEY `students_guardian_id_foreign` (`guardian_id`),
  KEY `students_session_year_id_foreign` (`session_year_id`),
  CONSTRAINT `students_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `students_guardian_id_foreign` FOREIGN KEY (`guardian_id`) REFERENCES `users` (`id`),
  CONSTRAINT `students_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `students_session_year_id_foreign` FOREIGN KEY (`session_year_id`) REFERENCES `session_years` (`id`),
  CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subject_teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject_teachers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_section_id` bigint(20) unsigned NOT NULL,
  `subject_id` bigint(20) unsigned NOT NULL,
  `teacher_id` bigint(20) unsigned NOT NULL COMMENT 'user_id',
  `class_subject_id` bigint(20) unsigned NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_ids` (`class_section_id`,`class_subject_id`,`teacher_id`),
  KEY `subject_teachers_school_id_foreign` (`school_id`),
  KEY `subject_teachers_subject_id_foreign` (`subject_id`),
  KEY `subject_teachers_teacher_id_foreign` (`teacher_id`),
  KEY `subject_teachers_class_subject_id_foreign` (`class_subject_id`),
  CONSTRAINT `subject_teachers_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `subject_teachers_class_subject_id_foreign` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`id`),
  CONSTRAINT `subject_teachers_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subject_teachers_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  CONSTRAINT `subject_teachers_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subjects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bg_color` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `medium_id` bigint(20) unsigned NOT NULL,
  `type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Theory / Practical',
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subjects_school_id_foreign` (`school_id`),
  KEY `subjects_medium_id_foreign` (`medium_id`),
  CONSTRAINT `subjects_medium_id_foreign` FOREIGN KEY (`medium_id`) REFERENCES `mediums` (`id`),
  CONSTRAINT `subjects_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subscription_bills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscription_bills` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) unsigned NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(8,4) NOT NULL,
  `total_student` bigint(20) NOT NULL,
  `total_staff` bigint(20) NOT NULL,
  `payment_transaction_id` bigint(20) unsigned DEFAULT NULL,
  `due_date` date NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_bill` (`subscription_id`,`school_id`),
  KEY `subscription_bills_school_id_foreign` (`school_id`),
  KEY `subscription_bills_payment_transaction_id_foreign` (`payment_transaction_id`),
  CONSTRAINT `subscription_bills_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`),
  CONSTRAINT `subscription_bills_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscription_bills_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subscription_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscription_features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) unsigned NOT NULL,
  `feature_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`subscription_id`,`feature_id`),
  KEY `subscription_features_feature_id_foreign` (`feature_id`),
  CONSTRAINT `subscription_features_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`),
  CONSTRAINT `subscription_features_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint(20) unsigned NOT NULL,
  `package_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_charge` double(8,4) NOT NULL,
  `staff_charge` double(8,4) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `billing_cycle` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_package_id_foreign` (`package_id`),
  KEY `subscriptions_school_id_foreign` (`school_id`),
  CONSTRAINT `subscriptions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  CONSTRAINT `subscriptions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'datatype like string , file etc',
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_settings_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `telescope_entries` (
  `sequence` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `timetables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetables` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subject_teacher_id` bigint(20) unsigned DEFAULT NULL,
  `class_section_id` bigint(20) unsigned NOT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `note` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('Lecture','Break') COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester_id` bigint(20) unsigned DEFAULT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `timetables_subject_teacher_id_foreign` (`subject_teacher_id`),
  KEY `timetables_school_id_foreign` (`school_id`),
  KEY `timetables_class_section_id_foreign` (`class_section_id`),
  KEY `timetables_subject_id_foreign` (`subject_id`),
  KEY `timetables_semester_id_foreign` (`semester_id`),
  CONSTRAINT `timetables_class_section_id_foreign` FOREIGN KEY (`class_section_id`) REFERENCES `class_sections` (`id`),
  CONSTRAINT `timetables_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timetables_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`),
  CONSTRAINT `timetables_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  CONSTRAINT `timetables_subject_teacher_id_foreign` FOREIGN KEY (`subject_teacher_id`) REFERENCES `subject_teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_status_for_next_cycles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_status_for_next_cycles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `status` int(11) NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_status_for_next_cycles_user_id_unique` (`user_id`),
  KEY `user_status_for_next_cycles_school_id_foreign` (`school_id`),
  CONSTRAINT `user_status_for_next_cycles_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_status_for_next_cycles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT 'user/667524605a9072.287647681718953056.png',
  `dob` date DEFAULT NULL,
  `current_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `reset_request` tinyint(4) NOT NULL DEFAULT '0',
  `fcm_id` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_id` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_school_id_foreign` (`school_id`),
  CONSTRAINT `users_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2022_04_01_091033_create_permission_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2022_04_01_105826_all_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2023_11_16_134449_version1-0-1',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2023_12_07_120054_version1_1_0',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2018_08_08_100000_create_telescope_entries_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2024_06_29_143052_add_fees_type_id_to_fees_installments_table',3);

INSERT INTO `system_settings` (`id`, `name`, `data`, `type`) VALUES
(1, 'time_zone', 'Africa/Nairobi', 'string'),
(2, 'date_format', 'jS F Y', 'date'),
(3, 'time_format', 'h:i A', 'time'),
(4, 'theme_color', '#22577A', 'string'),
(5, 'session_year', '1', 'string'),
(6, 'system_version', '1.1.1', 'string'),
(7, 'email_verified', '1', 'string'),
(8, 'subscription_alert', '7', 'integer'),
(9, 'currency_code', 'KES', 'string'),
(10, 'currency_symbol', 'Kshs.', 'string'),
(11, 'additional_billing_days', '5', 'integer'),
(12, 'system_name', 'Edusphere', 'string'),
(13, 'address', 'Mugi Road, Nairobi Kenya', 'string'),
(14, 'billing_cycle_in_days', '30', 'integer'),
(15, 'current_plan_expiry_warning_days', '7', 'integer'),
(16, 'mail_mailer', 'smtp', 'string'),
(17, 'mail_host', 'mail.qtechafrica.com', 'string'),
(18, 'mail_port', '465', 'string'),
(19, 'mail_username', 'mail-delivery@qtechafrica.com', 'string'),
(20, 'mail_password', 'DH#!&t%]a4sw', 'string'),
(21, 'mail_encryption', 'tls', 'string'),
(22, 'mail_send_from', 'mailing@edusphere.co.ke', 'string'),
(24, 'trial_days', '30', 'text'),
(25, 'student_limit', '50', 'text'),
(26, 'staff_limit', '5', 'text'),
(30, 'tag_line', 'Powering Schools to inspiring all students to excel as life long learners', 'string'),
(31, 'mobile', '254747589205', 'string'),
(47, 'horizontal_logo', 'no_image_available.jpg', 'file'),
(48, 'vertical_logo', 'no_image_available.jpg', 'file'),
(49, 'favicon', 'no_image_available.jpg', 'file'),
(50, 'login_page_logo', 'no_image_available.jpg', 'file'),
(51, 'privacy_policy', '&lt;h3&gt;Edusphere Privacy Policy&lt;/h3&gt;\n&lt;p&gt;&lt;strong&gt;Effective Date:&lt;/strong&gt; 6th August 2024&lt;/p&gt;\n&lt;p&gt;&lt;strong&gt;1. Introduction&lt;/strong&gt;&lt;/p&gt;\n&lt;p&gt;Welcome to Edusphere, a school management system provided as a Software as a Service (SaaS) by [Your Company Name] (&quot;we&quot;, &quot;our&quot;, &quot;us&quot;). We are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our service.&lt;/p&gt;\n&lt;p&gt;&lt;strong&gt;2. Information We Collect&lt;/strong&gt;&lt;/p&gt;\n&lt;p&gt;We may collect information about you in a variety of ways. The information we may collect includes:&lt;/p&gt;\n&lt;ul&gt;\n&lt;li&gt;\n&lt;p&gt;&lt;strong&gt;Personal Data:&lt;/strong&gt; Personally identifiable information, such as your name, address, email address, and telephone number, and demographic information, such as your age, gender, and location, that you voluntarily give to us when you register with the service or when you choose to participate in various activities related to the service.&lt;/p&gt;\n&lt;/li&gt;\n&lt;li&gt;\n&lt;p&gt;&lt;strong&gt;Institutional Data:&lt;/strong&gt; Information about the school or institution using our service, such as school name, address, contact details, and enrollment information.&lt;/p&gt;\n&lt;/li&gt;\n&lt;li&gt;\n&lt;p&gt;&lt;strong&gt;Student Data:&lt;/strong&gt; Information about students, including names, grades, attendance records, and other educational records that are uploaded to the system.&lt;/p&gt;\n&lt;/li&gt;\n&lt;li&gt;\n&lt;p&gt;&lt;strong&gt;Teacher Data:&lt;/strong&gt; Information about teachers, including names, contact details, employment records, and other professional information.&lt;/p&gt;\n&lt;/li&gt;\n&lt;li&gt;\n&lt;p&gt;&lt;strong&gt;Usage Data:&lt;/strong&gt; Information our servers automatically collect when you access the service, such as your IP address, browser type, operating system, access times, and the pages you have viewed directly before and after accessing the service.&lt;/p&gt;\n&lt;/li&gt;\n&lt;/ul&gt;\n&lt;p&gt;&lt;strong&gt;3. How We Use Your Information&lt;/strong&gt;&lt;/p&gt;\n&lt;p&gt;We use the information we collect in the following ways:&lt;/p&gt;\n&lt;ul&gt;\n&lt;li&gt;To provide, operate, and maintain our service&lt;/li&gt;\n&lt;li&gt;To improve, personalize, and expand our service&lt;/li&gt;\n&lt;li&gt;To understand and analyze how you use our service&lt;/li&gt;\n&lt;li&gt;To develop new products, services, features,&lt;/li&gt;\n&lt;/ul&gt;', 'string');


INSERT INTO `features` (`id`, `name`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Student Management', 1, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(2, 'Academics Management', 1, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(3, 'Slider Management', 0, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(4, 'Teacher Management', 1, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(5, 'Session Year Management', 1, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(6, 'Holiday Management', 0, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(7, 'Timetable Management', 0, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(8, 'Attendance Management', 0, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(9, 'Exam Management', 0, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(10, 'Lesson Management', 0, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(11, 'Assignment Management', 0, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(12, 'Announcement Management', 0, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(13, 'Staff Management', 0, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(14, 'Expense Management', 0, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(15, 'Staff Leave Management', 0, '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(16, 'Fees Management', 0, '2024-02-15 17:56:33', '2024-02-15 17:56:33');

INSERT INTO `faqs` (`id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'What features does Edusphere offer for school management?', 'Edusphere provides a comprehensive suite of features including student enrollment and attendance tracking, grade management, timetable scheduling, parent-teacher communication, online fee payment, and reporting and analytics. It also includes modules for library management, transportation tracking, and extracurricular activities.', '2024-07-07 20:51:50', '2024-07-07 20:51:50'),
(2, 'How secure is my data on Edusphere?', 'Edusphere takes data security very seriously. We use advanced encryption protocols to ensure that all data is securely transmitted and stored. Regular security audits and compliance with industry standards like GDPR and FERPA ensure that your data is protected against unauthorized access and breaches.', '2024-07-07 20:52:08', '2024-07-07 20:52:08'),
(3, 'Can Edusphere be customized to meet the specific needs of our school?', 'Yes, Edusphere is highly customizable. You can tailor the platform to match your school\'s specific processes and requirements. Our team works closely with you to configure and implement the system in a way that aligns with your school\'s unique needs.', '2024-07-07 20:52:24', '2024-07-07 20:52:24'),
(4, 'How do teachers and parents access Edusphere?', 'Teachers and parents can access Edusphere through our web-based portal or mobile app, available for both iOS and Android devices. Each user receives a unique login, ensuring that they can securely access the information relevant to them. The interface is user-friendly and designed to be intuitive for all users.', '2024-07-07 20:52:37', '2024-07-07 20:52:37'),
(5, 'What kind of support is available if we encounter issues with Edusphere?', 'Edusphere offers robust customer support to ensure that any issues are quickly resolved. Our support team is available 24/7 via email, phone, and live chat. Additionally, we provide an extensive knowledge base and video tutorials to help you get the most out of the system. For critical issues, we have an escalation process to ensure timely resolution.', '2024-07-07 20:52:53', '2024-07-07 20:52:53');


INSERT INTO `users` (`id`, `first_name`, `last_name`, `mobile`, `email`, `password`, `gender`, `image`, `dob`, `current_address`, `permanent_address`, `occupation`, `status`, `reset_request`, `fcm_id`, `school_id`, `remember_token`, `email_verified_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Default', 'System Admin', '', 'defaultadmin@edusphere.co.ke', '$2y$10$bugx.Celu7sT9UUzoOJE9eEorfkDR4KYWufNYazPwWxKwYXDGdfZm', 'male', 'logo.svg', NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, 'gKhuHgDk8OEdjyiyzyXfCN6ud4yMleDGsUSoKro8saRp60Dbf7CaI1i766IS', NULL, '2023-11-25 05:38:41', '2024-06-28 14:49:33', NULL);

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'role-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(2, 'role-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(3, 'role-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(4, 'role-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(5, 'medium-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(6, 'medium-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(7, 'medium-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(8, 'medium-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(9, 'section-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(10, 'section-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(11, 'section-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(12, 'section-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(13, 'class-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(14, 'class-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(15, 'class-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(16, 'class-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(17, 'class-section-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(18, 'class-section-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(19, 'class-section-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(20, 'class-section-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(21, 'subject-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(22, 'subject-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(23, 'subject-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(24, 'subject-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(25, 'teacher-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(26, 'teacher-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(27, 'teacher-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(28, 'teacher-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(29, 'guardian-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(30, 'guardian-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(31, 'guardian-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(32, 'guardian-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(33, 'session-year-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(34, 'session-year-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(35, 'session-year-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(36, 'session-year-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(37, 'student-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(38, 'student-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(39, 'student-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(40, 'student-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(41, 'timetable-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(42, 'timetable-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(43, 'timetable-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(44, 'timetable-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(45, 'attendance-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(46, 'attendance-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(47, 'attendance-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(48, 'attendance-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(49, 'holiday-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(50, 'holiday-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(51, 'holiday-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(52, 'holiday-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(53, 'announcement-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(54, 'announcement-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(55, 'announcement-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(56, 'announcement-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(57, 'slider-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(58, 'slider-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(59, 'slider-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(60, 'slider-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(61, 'promote-student-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(62, 'promote-student-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(63, 'promote-student-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(64, 'promote-student-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(65, 'language-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(66, 'language-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(67, 'language-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(68, 'language-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(69, 'lesson-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(70, 'lesson-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(71, 'lesson-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(72, 'lesson-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(73, 'topic-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(74, 'topic-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(75, 'topic-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(76, 'topic-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(77, 'schools-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(78, 'schools-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(79, 'schools-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(80, 'schools-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(81, 'form-fields-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(82, 'form-fields-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(83, 'form-fields-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(84, 'form-fields-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(85, 'grade-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(86, 'grade-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(87, 'grade-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(88, 'grade-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(89, 'package-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(90, 'package-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(91, 'package-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(92, 'package-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(93, 'addons-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(94, 'addons-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(95, 'addons-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(96, 'addons-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(97, 'assignment-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(98, 'assignment-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(99, 'assignment-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(100, 'assignment-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(101, 'assignment-submission', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(102, 'exam-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(103, 'exam-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(104, 'exam-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(105, 'exam-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(106, 'exam-timetable-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(107, 'exam-timetable-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(108, 'exam-timetable-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(109, 'exam-timetable-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(110, 'exam-upload-marks', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(111, 'exam-result', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(112, 'system-setting-manage', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(113, 'fcm-setting-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(114, 'email-setting-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(115, 'privacy-policy', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(116, 'contact-us', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(117, 'about-us', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(118, 'terms-condition', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(119, 'class-teacher', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(120, 'student-reset-password', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(121, 'reset-password-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(122, 'student-change-password', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(123, 'update-admin-profile', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(124, 'fees-classes', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(125, 'fees-paid', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(126, 'fees-config', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(127, 'school-setting-manage', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(128, 'app-settings', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(129, 'subscription-view', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(130, 'online-exam-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(131, 'online-exam-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(132, 'online-exam-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(133, 'online-exam-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(134, 'online-exam-questions-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(135, 'online-exam-questions-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(136, 'online-exam-questions-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(137, 'online-exam-questions-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(138, 'fees-type-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(139, 'fees-type-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(140, 'fees-type-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(141, 'fees-type-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(142, 'fees-class-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(143, 'fees-class-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(144, 'fees-class-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(145, 'fees-class-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(146, 'staff-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(147, 'staff-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(148, 'staff-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(149, 'staff-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(150, 'expense-category-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(151, 'expense-category-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(152, 'expense-category-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(153, 'expense-category-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(154, 'expense-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(155, 'expense-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(156, 'expense-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(157, 'expense-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(158, 'semester-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(159, 'semester-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(160, 'semester-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(161, 'semester-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(162, 'payroll-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(163, 'payroll-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(164, 'payroll-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(165, 'payroll-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(166, 'stream-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(167, 'stream-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(168, 'stream-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(169, 'stream-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(170, 'shift-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(171, 'shift-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(172, 'shift-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(173, 'shift-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(174, 'faqs-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(175, 'faqs-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(176, 'faqs-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(177, 'faqs-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(178, 'online-exam-result-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(179, 'fcm-setting-manage', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(180, 'fees-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(181, 'fees-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(182, 'fees-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(183, 'fees-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(184, 'transfer-student-list', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(185, 'transfer-student-create', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(186, 'transfer-student-edit', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(187, 'transfer-student-delete', 'web', '2023-11-25 05:38:41', '2024-02-15 17:56:33'),
(188, 'guidance-list', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(189, 'guidance-create', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(190, 'guidance-edit', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(191, 'guidance-delete', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(192, 'leave-list', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(193, 'leave-create', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(194, 'leave-edit', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(195, 'leave-delete', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(196, 'approve-leave', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(197, 'front-site-setting', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(198, 'payment-settings', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(199, 'subscription-settings', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(200, 'subscription-change-bills', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33'),
(201, 'school-terms-condition', 'web', '2024-02-15 17:56:33', '2024-02-15 17:56:33');

INSERT INTO `roles` (`id`, `name`, `guard_name`, `school_id`, `custom_role`, `editable`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', NULL, 0, 0, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(2, 'School Admin', 'web', NULL, 0, 0, '2023-11-25 05:38:41', '2023-11-25 05:38:41'),
(3, 'Teacher', 'web', NULL, 1, 1, '2024-02-15 17:56:33', '2024-02-15 17:56:33');

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(123, 1),
(128, 1),
(129, 1),
(146, 1),
(147, 1),
(148, 1),
(149, 1),
(174, 1),
(175, 1),
(176, 1),
(177, 1),
(179, 1),
(188, 1),
(189, 1),
(190, 1),
(191, 1),
(197, 1),
(199, 1),
(200, 1),
(201, 1);

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

INSERT INTO `packages` (`id`, `name`, `description`, `tagline`, `student_charge`, `staff_charge`, `status`, `is_trial`, `highlight`, `rank`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Demo Package', 'This is a package for schools', 'Empowering Efficiency, Virtually', 1.0000, 1.0000, 0, 0, 1, 1, '2024-06-21 09:26:05', '2024-06-29 17:21:59', NULL),
(2, 'Bronse', 'This contains a few features', NULL, 100.0000, 100.0000, 0, 0, 1, 2, '2024-06-28 15:11:11', '2024-06-29 17:25:06', '2024-06-29 17:25:06'),
(3, 'Trial Package', 'Bronse Package', NULL, 0.0000, 0.0000, 1, 1, 0, -1, '2024-06-28 15:15:48', '2024-08-02 09:24:17', NULL),
(4, 'Edusphere Standard', NULL, NULL, 10.0000, 15.0000, 1, 0, 1, 0, '2024-06-29 20:02:33', '2024-06-29 20:04:49', NULL);

