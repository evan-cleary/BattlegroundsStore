
-- ----------------------------
-- Table structure for `battlegrounds_store_items`
-- Replace "battlegrounds_" with your database prefix.
-- ----------------------------
ALTER TABLE `battlegrounds_players`
    ADD COLUMN `authcode`  varchar(18) NULL AFTER `player_name`,
    ADD COLUMN `authToken`  varchar(128) NULL AFTER `authcode`;