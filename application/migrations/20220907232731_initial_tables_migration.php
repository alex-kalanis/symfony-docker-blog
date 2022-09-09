<?php
declare(strict_types=1);


use Phinx\Migration\AbstractMigration;


final class InitialTablesMigration extends AbstractMigration
{
    public function up(): void
    {
        $this->query('DROP TABLE IF EXISTS `groups`;');
        $this->query('CREATE TABLE `groups` (
  `gr_id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `gr_name` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `gr_desc` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `gr_deleted` int NULL
) COMMENT=\'groups which can edit things\' COLLATE \'utf8mb4_general_ci\';');
        $this->query('ALTER TABLE `groups`
ADD INDEX `gr_deleted` (`gr_deleted`);');
        $this->query('DROP TABLE IF EXISTS `users`;');
        $this->query('CREATE TABLE `users` (
  `u_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `u_login` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `u_pass` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `cl_id` int(10) unsigned NOT NULL,
  `gr_id` int(10) unsigned NOT NULL,
  `u_display` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `u_deleted` datetime NULL
) COMMENT=\'users in system\' COLLATE \'utf8mb4_general_ci\';');
        $this->query('ALTER TABLE `users`
ADD UNIQUE `u_login` (`u_login`(120)),
ADD INDEX `cl_id` (`cl_id`),
ADD INDEX `gr_id` (`gr_id`),
ADD INDEX `u_deleted` (`u_deleted`);');
        $this->query('DROP TABLE IF EXISTS `articles`;');
        $this->query('CREATE TABLE `articles` (
  `a_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `u_id` int(11) unsigned NOT NULL,
  `a_title` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `a_content` text COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `a_publish` datetime NULL,
  `a_deleted` datetime NULL
) COMMENT=\'simple blog articles\' COLLATE \'utf8mb4_general_ci\';');
        $this->query('ALTER TABLE `articles`
ADD INDEX `u_id` (`u_id`),
ADD INDEX `a_publish` (`a_publish`),
ADD INDEX `a_deleted` (`a_deleted`);');
        $this->query('DROP TABLE IF EXISTS `configs`;');
        $this->query('CREATE TABLE `configs` (
  `cfg_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `cfg_key` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `cfg_value` text COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `cfg_deleted` datetime NULL
) COMMENT=\'changeable configs\' COLLATE \'utf8mb4_general_ci\';');
        $this->query('ALTER TABLE `configs`
ADD INDEX `cfg_key` (`cfg_key`(512)),
ADD INDEX `cfg_deleted` (`cfg_deleted`);');
        $this->query('DROP TABLE IF EXISTS `logs`;');
        $this->query('CREATE TABLE `logs` (
  `log_id` int(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `log_type` int(10) unsigned NOT NULL,
  `log_source` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `log_name` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `log_trace` text COLLATE \'utf8mb4_general_ci\' NOT NULL
) COMMENT=\'address book table\' COLLATE \'utf8mb4_general_ci\';');
        $this->query('ALTER TABLE `logs`
ADD INDEX `log_type` (`log_type`);');
        $this->query('ALTER TABLE `users`
ADD FOREIGN KEY (`gr_id`) REFERENCES `groups` (`gr_id`) ON DELETE RESTRICT ON UPDATE CASCADE;');
        $this->query('ALTER TABLE `articles`
ADD FOREIGN KEY (`u_id`) REFERENCES `users` (`u_id`) ON DELETE RESTRICT ON UPDATE CASCADE;');
        $this->query('INSERT INTO `groups` (`gr_name`, `gr_desc`, `gr_deleted`)
VALUES (\'System\', \'Allow all\', NULL),  (\'Maintain\', \'Sysops\', NULL),  (\'Users\', \'Basic users\', NULL);');
        $this->query('INSERT INTO `users` (`u_login`, `u_pass`, `cl_id`, `gr_id`, `u_display`, `u_deleted`)
VALUES (\'FirstOne\', \'$2y$10$X2sGPUqfWKk27liuUTJbyO.aqTuUe6NhC1peYfnM9QMX0Ady.rUeW\', \'1\', \'2\', \'First One\', NULL);');
    }

    public function down(): void
    {
        $this->query('DROP TABLE IF EXISTS `logs`;');
        $this->query('DROP TABLE IF EXISTS `configs`;');
        $this->query('DROP TABLE IF EXISTS `articles`;');
        $this->query('DROP TABLE IF EXISTS `users`;');
        $this->query('DROP TABLE IF EXISTS `groups`;');
    }
}
