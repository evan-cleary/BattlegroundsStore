SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `battlegrounds_store_items`
-- Replace "battlegrounds_" with your database prefix.
-- ----------------------------
DROP TABLE IF EXISTS `battlegrounds_store_items`;
CREATE TABLE `battlegrounds_store_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `item` varchar(255) NOT NULL,
  `damage` int(11) DEFAULT NULL,
  `attributes` text,
  `price` int(11) NOT NULL DEFAULT '0',
  `amount` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of battlegrounds_store_items
-- ----------------------------
