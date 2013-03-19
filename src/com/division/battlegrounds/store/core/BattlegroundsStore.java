package com.division.battlegrounds.store.core;

import com.division.battlegrounds.core.BattlegroundCore;
import com.division.battlegrounds.datasource.DataInterface;
import com.division.battlegrounds.datasource.MySQLStore;
import java.security.SecureRandom;
import java.sql.SQLException;
import org.bukkit.Bukkit;
import org.bukkit.plugin.java.JavaPlugin;

/**
 *
 * @author Evan
 */
public class BattlegroundsStore extends JavaPlugin {

    private DataInterface data;
    private static SecureRandom secRan = new SecureRandom();
    private MySQLStore store;
    private static BattlegroundsStore instance;

    @Override
    public void onEnable() {
        data = BattlegroundCore.getInstance().getDatabases();
        store = new MySQLStore(data.getRawConnection());
        try {
            store.setup();
        } catch (SQLException ex) {
            Bukkit.getServer().getPluginManager().disablePlugin(this);
            System.out.println("Error while setting up databases. Plugin aborting.");
            return;
        }
        instance = this;
    }

    public MySQLStore getStore() {
        return store;
    }

    public static BattlegroundsStore getInstance() {
        return instance;
    }

    public static SecureRandom getSecure() {
        return secRan;
    }
}
