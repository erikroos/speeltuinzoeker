ALTER TABLE `speeltuin`
  ADD COLUMN `openingstijden`  text NOT NULL AFTER `avg_rating`,
  ADD COLUMN `vergoeding`  text NOT NULL AFTER `openingstijden`;
  
CREATE TABLE IF NOT EXISTS `bericht_gelezen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bericht_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;