SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `battlegrounds_purchases`
-- Replace "battlegrounds_" with your database prefix.
-- ----------------------------
DROP TABLE IF EXISTS `battlegrounds_purchases`;
CREATE TABLE `battlegrounds_purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `delivered` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of battlegrounds_purchases
-- ----------------------------
