package com.division.battlegrounds.datasource;

import com.division.battlegrounds.config.BattlegroundsConfig;
import com.division.battlegrounds.core.BattlegroundCore;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.HashMap;
import java.util.Map;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.bukkit.ChatColor;
import org.bukkit.enchantments.Enchantment;
import org.bukkit.entity.Player;
import org.bukkit.inventory.ItemStack;

/**
 *
 * @author Evan
 */
public class MySQLStore {
    
    private Connection conn;
    private BattlegroundsConfig config;
    private DataInterface db;
    
    public MySQLStore(Connection conn) {
        this.conn = conn;
        config = BattlegroundCore.getInstance().getBCConfig();
        db = BattlegroundCore.getInstance().getDatabases();
    }
    
    public void setup() throws SQLException {
        Statement st = null;
        try {
            st = conn.createStatement();
            st.executeUpdate("CREATE TABLE IF NOT EXISTS `" + config.getTablePrefix() + "store_items` ("
                    + "`id` int(11) NOT NULL AUTO_INCREMENT,"
                    + "`item_id` int(11) NOT NULL,"
                    + "`item` varchar(255) NOT NULL,"
                    + "`damage` int(11) DEFAULT NULL,"
                    + "`attributes` text,"
                    + "`price` int(11) NOT NULL DEFAULT '0',"
                    + "`amount` tinyint(4) DEFAULT '1',"
                    + "PRIMARY KEY (`id`)"
                    + ") ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            st.executeUpdate("CREATE TABLE IF NOT EXISTS `" + config.getTablePrefix() + "purchases` ("
                    + "`id` int(11) NOT NULL AUTO_INCREMENT,"
                    + "`item_id` int(11) NOT NULL,"
                    + "`player_id` int(11) NOT NULL,"
                    + "`delivered` tinyint(4) DEFAULT '0',"
                    + "PRIMARY KEY (`id`)"
                    + ") ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            /*st.executeUpdate("ALTER IGNORE TABLE `" + config.getTablePrefix() + "_players`"
                    + "ADD COLUMN `authcode`  varchar(18) NULL AFTER `player_name`,"
                    + "ADD COLUMN `authToken`  varchar(128) NULL AFTER `authcode`;");*/
            
        } finally {
            if (st != null) {
                try {
                    st.close();
                } catch (SQLException ex) {
                }
            }
        }
    }
    
    public void updateAccessToken(String player_name, String accessToken) {
        int player_id = db.getPlayerId(player_name);
        PreparedStatement pst = null;
        try {
            pst = conn.prepareStatement("UPDATE " + config.getTablePrefix() + "players SET authcode=? WHERE id=?");
            pst.setString(1, accessToken);
            pst.setInt(2, player_id);
            pst.executeUpdate();
        } catch (SQLException ex) {
        } finally {
            if (pst != null) {
                try {
                    pst.close();
                } catch (SQLException ex) {
                }
            }
        }
    }
    
    public int getHistoryCount(String player_name) {
        int player_id = db.getPlayerId(player_name);
        PreparedStatement pst = null;
        ResultSet rs = null;
        try {
            pst = conn.prepareStatement("SELECT COUNT(*) FROM " + config.getTablePrefix() + "purchases WHERE player_id=?");
            pst.setInt(1, player_id);
            rs = pst.executeQuery();
            if (rs.next()) {
                return rs.getInt(1);
            }
        } catch (Exception ex) {
        }
        return 0;
    }
    
    public String getHistory(String player_name) {
        return getHistory(player_name, 1);
    }
    
    public String getHistory(String player_name, int page) {
        String output = "";
        String historyFormat = "%s%s" + ChatColor.DARK_AQUA + " -- " + ChatColor.YELLOW + "%s";
        int player_id = db.getPlayerId(player_name);
        int max = 10 + (10 * (page - 1));
        int min = 0 + (10 * (page - 1));
        PreparedStatement pst = null;
        ResultSet rs = null;
        try {
            pst = conn.prepareStatement("SELECT * FROM " + config.getTablePrefix() + "purchases WHERE player_id=? ORDER BY id ASC LIMIT " + min + "," + max);
            pst.setInt(1, player_id);
            rs = pst.executeQuery();
            while (rs.next()) {
                ResultSet rs2 = null;
                pst = conn.prepareStatement("SELECT item FROM " + config.getTablePrefix() + "store_items WHERE id=?");
                pst.setInt(1, rs.getInt("item_id"));
                rs2 = pst.executeQuery();
                rs2.first();
                String item_name = rs2.getString("item");
                if (!rs.getBoolean("delivered")) {
                    output += String.format(historyFormat, ChatColor.GREEN, rs.getInt("id"), item_name) + ":";
                } else {
                    output += String.format(historyFormat, ChatColor.GRAY, rs.getInt("id"), item_name) + ":";
                }
            }
        } catch (SQLException ex) {
        } finally {
            if (pst != null) {
                try {
                    pst.close();
                } catch (SQLException ex) {
                }
            }
            if (rs != null) {
                try {
                    rs.close();
                } catch (SQLException ex) {
                }
            }
        }
        if (output.isEmpty()) {
            return "No transaction history.";
        }
        return output;
    }
    
    public boolean addItem(ItemStack item, int price, int amount) {
        PreparedStatement pst = null;
        try {
            pst = conn.prepareStatement("INSERT INTO " + config.getTablePrefix() + "store_items(item_id,item,damage,attributes,price,amount) VALUES(?,?,?,?,?,?)");
            pst.setInt(1, item.getTypeId());
            pst.setString(2, item.getType().name().toLowerCase().replace("_", " "));
            pst.setInt(3, item.getDurability());
            pst.setString(4, decompileEnchants(item));
            pst.setInt(5, price);
            if (amount == -1) {
                pst.setInt(6, item.getAmount());
            } else {
                pst.setInt(6, amount);
            }
            int d = pst.executeUpdate();
            if (d != 0) {
                return true;
            }
        } catch (SQLException ex) {
        } finally {
            if (pst != null) {
                try {
                    pst.close();
                } catch (SQLException ex) {
                }
            }
        }
        return false;
    }
    
    public boolean deliverItem(int transaction_id, Player receiver) {
        PreparedStatement pst = null;
        ResultSet rs = null;
        int player_id = db.getPlayerId(receiver.getName());
        try {
            pst = conn.prepareStatement("SELECT * FROM " + config.getTablePrefix() + "purchases WHERE id=? AND player_id=? AND delivered=0");
            pst.setInt(1, transaction_id);
            pst.setInt(2, player_id);
            rs = pst.executeQuery();
            if (rs.first()) {
                int store_id = rs.getInt("item_id");
                ItemStack is = createItem(store_id);
                if (is == null) {
                    receiver.sendMessage(String.format(BattlegroundCore.logFormat, "An error occured when creating the item. Please contact an admin."));
                    return false;
                }
                if (receiver.getInventory().firstEmpty() == -1) {
                    receiver.sendMessage(String.format(BattlegroundCore.logFormat, "Your inventory is full. Please make room."));
                    return false;
                }
                receiver.getInventory().addItem(is);
                pst = conn.prepareStatement("UPDATE " + config.getTablePrefix() + "purchases SET delivered=1 WHERE id=" + transaction_id);
                pst.executeUpdate();
                return true;
            } else {
                receiver.sendMessage(String.format(BattlegroundCore.logFormat, "That transaction either doesn't exist or has already been delivered."));
                return false;
            }
        } catch (SQLException ex) {
        } finally {
            if (pst != null) {
                try {
                    pst.close();
                } catch (SQLException ex) {
                }
            }
            if (rs != null) {
                try {
                    rs.close();
                } catch (SQLException ex) {
                }
            }
        }
        return false;
    }
    
    private ItemStack createItem(int store_id) {
        PreparedStatement pst = null;
        ResultSet rs = null;
        try {
            pst = conn.prepareStatement("SELECT * FROM " + config.getTablePrefix() + "store_items WHERE id=?");
            pst.setInt(1, store_id);
            rs = pst.executeQuery();
            if (rs.first()) {
                ItemStack newItemStack = new ItemStack(rs.getInt("item_id"), rs.getInt("amount"), rs.getShort("damage"));
                String enchs = rs.getString("attributes");
                if (!enchs.isEmpty()) {
                    newItemStack.addEnchantments(recompileEnchants(enchs));
                }
                return newItemStack;
            }
        } catch (SQLException ex) {
            Logger.getLogger(MySQLStore.class.getName()).log(Level.SEVERE, null, ex);
        } finally {
            if (pst != null) {
                try {
                    pst.close();
                } catch (SQLException ex) {
                }
            }
            if (rs != null) {
                try {
                    rs.close();
                } catch (SQLException ex) {
                }
            }
        }
        return null;
    }
    
    public boolean removeItem(int store_id) {
        PreparedStatement pst = null;
        try {
            pst = conn.prepareStatement("DELETE FROM " + config.getTablePrefix() + "store_items WHERE id=?");
            pst.setInt(1, store_id);
            int d = pst.executeUpdate();
            if (d != 0) {
                return true;
            }
        } catch (SQLException ex) {
        } finally {
            if (pst != null) {
                try {
                    pst.close();
                } catch (SQLException ex) {
                }
            }
        }
        return false;
    }
    
    private String decompileEnchants(ItemStack item) {
        String output = "";
        boolean run = false;
        for (Enchantment enc : item.getEnchantments().keySet()) {
            int id = enc.getId();
            int level = item.getEnchantments().get(enc);
            if (!run) {
                output += id + ":" + level;
                run = !run;
            } else {
                output += "," + id + ":" + level;
            }
        }
        return output;
    }
    
    private Map<Enchantment, Integer> recompileEnchants(String enchants) {
        Map<Enchantment, Integer> enchMap = new HashMap<Enchantment, Integer>();
        String[] enchs = enchants.split(",");
        for (String ench : enchs) {
            String[] enchBreak = ench.split(":");
            Enchantment enchant = Enchantment.getById(Integer.parseInt(enchBreak[0]));
            enchMap.put(enchant, Integer.parseInt(enchBreak[1]));
        }
        return enchMap;
    }
}
