# 使用WordPress6 开发的电子书在线阅读网站

这是一个WordPress 主题

搜索


```
-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2022-08-18 21:19:18
-- 服务器版本： 5.6.50-log
-- PHP 版本： 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- 数据库： `publicbook`
--

-- --------------------------------------------------------

--
-- 表的结构 `chapter`
--

CREATE TABLE `chapter` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '章节ID',
  `ebook_id` int(10) UNSIGNED NOT NULL COMMENT '电子书ID',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级章节',
  `user_id` int(11) DEFAULT NULL COMMENT '所属用户',
  `title` varchar(255) DEFAULT NULL COMMENT '章节名称',
  `content` mediumtext COMMENT '章节内容',
  `strlen` int(11) NOT NULL DEFAULT '0' COMMENT '字数',
  `status` enum('normal','draft','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(11) DEFAULT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='电子书章节数据表';

--
-- 转储表的索引
--

--
-- 表的索引 `chapter`
--
ALTER TABLE `chapter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ebookid` (`ebook_id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `status` (`status`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `chapter`
--
ALTER TABLE `chapter`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '章节ID';
COMMIT;

```