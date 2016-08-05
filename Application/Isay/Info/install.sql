-- -----------------------------
-- 表结构 `icn_isay`
-- -----------------------------
CREATE TABLE `icn_isay` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`uid` INT(11) NOT NULL,
	`title` VARCHAR(50) NOT NULL COMMENT '标题',
	`description` VARCHAR(200) NOT NULL COMMENT '描述',
	`category` INT(11) NOT NULL COMMENT '分类',
	`status` TINYINT(2) NOT NULL COMMENT '状态',
	`reason` VARCHAR(100) NOT NULL COMMENT '审核失败原因',
	`sort` INT(5) NOT NULL COMMENT '排序',
	`position` INT(4) NOT NULL COMMENT '定位，展示位',
	`cover` INT(11) NOT NULL COMMENT '封面',
	`view` INT(10) NOT NULL COMMENT '阅读量',
	`comment` INT(10) NOT NULL COMMENT '评论量',
	`collection` INT(10) NOT NULL COMMENT '收藏量',
	`dead_line` INT(11) NOT NULL COMMENT '有效期',
	`source` VARCHAR(200) NOT NULL COMMENT '来源url',
    `up` INT(10) NOT NULL COMMENT '点赞量',
    `down` INT(10) NOT NULL COMMENT '点踩量',
	`create_time` INT(11) NOT NULL,
	`update_time` INT(11) NOT NULL,
	PRIMARY KEY (`id`)
)
COMMENT='爱说'
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=2;



-- -----------------------------
-- 表结构 `icn_isay_detail`
-- -----------------------------
CREATE TABLE `icn_isay_detail` (
	`isay_id` INT(11) NOT NULL,
	`content` TEXT NOT NULL COMMENT '内容',
	`template` VARCHAR(50) NULL DEFAULT NULL COMMENT '模板',
	PRIMARY KEY (`isay_id`)
)
COMMENT='爱说详情'
COLLATE='utf8_general_ci'
ENGINE=MyISAM;


-- -----------------------------
-- 表结构 `icn_isay_category`
-- -----------------------------
CREATE TABLE `icn_isay_category` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(20) NOT NULL,
	`pid` INT(11) NOT NULL,
	`can_post` TINYINT(4) NOT NULL COMMENT '前台可投稿',
	`need_audit` TINYINT(4) NOT NULL COMMENT '前台投稿是否需要审核',
	`sort` TINYINT(4) NOT NULL,
	`status` TINYINT(4) NOT NULL,
	PRIMARY KEY (`id`)
)
COMMENT='爱说分类'
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;



-- -----------------------------
-- 表结构 `icn_isay_like`
-- -----------------------------
CREATE TABLE `icn_isay_like` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`uid` INT(11) NOT NULL,
	`obj_id` INT(11) NOT NULL COMMENT '点赞对象id',
    `obj_type` ENUM('isay','comment') NOT NULL,
    `type` TINYINT(3) NOT NULL DEFAULT '0' COMMENT '点赞类型(1赞2踩)',
	`create_time` INT(11) NOT NULL,
	PRIMARY KEY (`id`)
)
COMMENT='爱说点赞'
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

-- -----------------------------
-- 表结构 `icn_isay_comment`
-- -----------------------------
CREATE TABLE `icn_isay_comment` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`uid` INT(11) NOT NULL,
	`obj_id` INT(11) NOT NULL,
    `obj_type` INT(11) NULL DEFAULT '0',
	`content` TEXT NOT NULL,
	`status` TINYINT(3) NOT NULL DEFAULT '0' COMMENT '点赞类型(0待审核，1通过，2未通过，4隐藏)',
	`comment` INT(11) NOT NULL COMMENT '子集回复统计',
	`up` INT(11) NOT NULL COMMENT '子集点赞量',
	`down` INT(11) NOT NULL COMMENT '子集点踩量',
	`to_comment_id` INT(11) NOT NULL,
	`create_time` INT(11) NOT NULL,
	`update_time` INT(11) NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

