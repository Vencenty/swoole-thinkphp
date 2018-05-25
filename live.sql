-- 球队表
CREATE TABLE `live_team` (
  `id` tinyint(1) unsigned NOT NULL auto_increment,
  `name`  VARCHAR(20) NOT NULL DEFAULT '',
  `image` VARCHAR(20) NOT NULL DEFAULT '',
  `type`  tinyint(1) unsigned NOT NULL DEFAULT 0,
  `create_time` int(10) unsigned NOT NULL DEFAULT 0,
  `update_time` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT charset=utf-8