<?php
declare(strict_types=1);


use Phinx\Migration\AbstractMigration;


final class InitialTablesMigration extends AbstractMigration
{
    public function up(): void
    {
        $this->query('DROP TABLE IF EXISTS `groups`;');
        $this->query('CREATE TABLE `groups` (
  `g_id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `g_name` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `g_desc` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `g_deleted` int NULL
) COMMENT=\'groups which can edit things\' COLLATE \'utf8mb4_general_ci\';');
        $this->query('ALTER TABLE `groups`
ADD INDEX `g_deleted` (`g_deleted`);');
        $this->query('DROP TABLE IF EXISTS `users`;');
        $this->query('CREATE TABLE `users` (
  `u_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `u_login` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `u_pass` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `u_class_id` int(10) unsigned NOT NULL,
  `u_group_id` int(10) unsigned NOT NULL,
  `u_display` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `u_deleted` datetime NULL
) COMMENT=\'users in system\' COLLATE \'utf8mb4_general_ci\';');
        $this->query('ALTER TABLE `users`
ADD INDEX `u_login` (`u_login`(120)),
ADD INDEX `u_class_id` (`u_class_id`),
ADD INDEX `u_group_id` (`u_group_id`),
ADD INDEX `u_deleted` (`u_deleted`);');
        $this->query('DROP TABLE IF EXISTS `articles`;');
        $this->query('CREATE TABLE `articles` (
  `a_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `u_id` int(11) unsigned NOT NULL,
  `a_title` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `a_content` text COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `a_publish` datetime NULL
  `a_deleted` datetime NULL
) COMMENT=\'simple blog articles\' COLLATE \'utf8mb4_general_ci\';');
        $this->query('ALTER TABLE `articles`
ADD INDEX `u_id` (`u_id`),
ADD INDEX `a_publish` (`a_publish`),
ADD INDEX `a_deleted` (`a_deleted`);');
        $this->query('DROP TABLE IF EXISTS `configs`;');
        $this->query('CREATE TABLE `configs` (
  `cfg_id` int(1024) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `cfg_key` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `cfg_value` text COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `cfg_deleted` datetime NULL
) COMMENT=\'changeable configs\' COLLATE \'utf8mb4_general_ci\';');
        $this->query('ALTER TABLE `configs`
ADD INDEX `cfg_key` (`cfg_key`(512)),
ADD INDEX `cfg_deleted` (`cfg_deleted`);');
        $this->query('DROP TABLE IF EXISTS `logs`;');
        $this->query('CREATE TABLE `logs` (
  `l_id` int(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `l_type` int(10) unsigned NOT NULL,
  `l_source` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `l_name` varchar(1024) COLLATE \'utf8mb4_general_ci\' NOT NULL,
  `l_trace` text COLLATE \'utf8mb4_general_ci\' NOT NULL
) COMMENT=\'address book table\' COLLATE \'utf8mb4_general_ci\';');
        $this->query('ALTER TABLE `logs`
ADD INDEX `l_type` (`l_type`);');
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
