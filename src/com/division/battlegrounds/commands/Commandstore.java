package com.division.battlegrounds.commands;

import com.division.battlegrounds.core.BattlegroundCore;
import com.division.battlegrounds.datasource.MySQLStore;
import com.division.battlegrounds.store.core.BattlegroundsStore;
import java.security.SecureRandom;
import javax.xml.bind.DatatypeConverter;
import org.bukkit.ChatColor;
import org.bukkit.entity.Player;

/**
 *
 * @author Evan
 */
public class Commandstore extends BattlegroundsCommand {
    
    @Override
    public void run(Player sender, String[] args) {
        MySQLStore store = BattlegroundsStore.getInstance().getStore();
        if (args.length <= 1) {
            sender.sendMessage(String.format(BattlegroundCore.logFormat, "Invalid Number of Arguements"));
            return;
        }
        String subCmd = args[1];
        if (subCmd.equalsIgnoreCase("help")) {
        } else if (subCmd.equalsIgnoreCase("history")) {
            if (!sender.hasPermission("battlegrounds.store.history")) {
                sender.sendMessage(String.format(BattlegroundCore.logFormat, "You do not have the required permissions."));
                return;
            }
            int count = store.getHistoryCount(sender.getName());
            int page = 1;
            sender.sendMessage(ChatColor.RED + "---------[" + ChatColor.GRAY + "Store History" + ChatColor.RED + "]---------");
            String history;
            if (args.length == 3) {
                page = Integer.parseInt(args[2]);
                history = store.getHistory(sender.getName(), page);
            } else {
                history = store.getHistory(sender.getName());
            }
            for (String s : history.split(":")) {
                if (!s.isEmpty()) {
                    sender.sendMessage(s);
                }
            }
            sender.sendMessage(ChatColor.RED + "---------=[" + ChatColor.GRAY + "Page(" + page + "/" + (int) Math.ceil(count / 10.0) + ")" + ChatColor.RED + "]=---------");
        } else if (subCmd.equalsIgnoreCase("add")) {
            if (!sender.hasPermission("battlegrounds.store.add")) {
                sender.sendMessage(String.format(BattlegroundCore.logFormat, "You do not have the required permissions."));
                return;
            }
            if (args.length >= 3) {
                int price = Integer.parseInt(args[2]);
                int amount = -1;
                if (args.length == 4) {
                    amount = Integer.parseInt(args[3]);
                }
                if (sender.getItemInHand().getTypeId() != 0) {
                    boolean success = store.addItem(sender.getItemInHand(), price, amount);
                    if (success) {
                        sender.sendMessage(String.format(BattlegroundCore.logFormat, "Item has been added to the store."));
                    } else {
                        sender.sendMessage(String.format(BattlegroundCore.logFormat, "Unable to create new store item. Please contact database administrator."));
                        
                    }
                } else {
                    sender.sendMessage(String.format(BattlegroundCore.logFormat, "You must have the item you wish to add to the store in your hand."));
                }
            }
        } else if (subCmd.equalsIgnoreCase("remove")) {
            if (!sender.hasPermission("battlegrounds.store.remove")) {
                sender.sendMessage(String.format(BattlegroundCore.logFormat, "You do not have the required permissions."));
                return;
            }
            if (args.length == 3) {
                int store_id = Integer.parseInt(args[2]);
                boolean success = store.removeItem(store_id);
                if (success) {
                    sender.sendMessage(String.format(BattlegroundCore.logFormat, "Store item " + args[2] + " has been removed."));
                } else {
                    sender.sendMessage(String.format(BattlegroundCore.logFormat, "Unable to remove store item " + args[2] + ". Please contact database administrator."));
                }
            }
        } else if (subCmd.equalsIgnoreCase("claim")) {
            if (!sender.hasPermission("battlegrounds.store.claim")) {
                sender.sendMessage(String.format(BattlegroundCore.logFormat, "You do not have the required permissions."));
                return;
            }
            if (args.length == 3) {
                int transaction_id = Integer.parseInt(args[2]);
                if (store.deliverItem(transaction_id, sender)) {
                    sender.sendMessage(String.format(BattlegroundCore.logFormat, "Your item has been successfully delivered."));
                }
            } else {
                sender.sendMessage(String.format(BattlegroundCore.logFormat, "Incorrect number of arguements."));
            }
        } else if (subCmd.equalsIgnoreCase("access")) {
            if (!sender.hasPermission("battlegrounds.store.access")) {
                sender.sendMessage(String.format(BattlegroundCore.logFormat, "You do not have the required permissions."));
                return;
            }
            
            byte[] secBytes = new byte[4];
            SecureRandom secRan = BattlegroundsStore.getSecure();
            secRan.nextBytes(secBytes);
            final String code = DatatypeConverter.printBase64Binary(secBytes).replace("==", "");
            sender.sendMessage(String.format(BattlegroundCore.logFormat, "You new store access code is: " + ChatColor.GOLD + code + ChatColor.RED + " (Case Sensative)"));
            store.updateAccessToken(sender.getName(), code);
        }
    }
}
