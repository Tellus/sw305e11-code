package sw3.server;

import java.sql.*;

/**
 * Strongly influenced by the SqlManager class in sw6.GirafPlaceServer, this
 * class serves to ease MySQL connection management and query execution.
 * It's important to note that an instance of this class maintains an open
 * connection to the MySQL database it targets; it does not open the connection
 * on a per-transaction basis (... yet, at least).
 * @author Johannes
 */
public class SqlHelper
{
    public java.sql.Connection connection;
    
    /**
     * Creates a new inactive SqlHelper instance.
     */
    public SqlHelper()
    {
        return;
    }
    
    /**
     * Creates a new SqlHelper instance and connects to a MySQL database on port
     * 3396 with the passed credentials..
     * @param host  Hostname or IP address to connect to.
     * @param user  Username to utilise.
     * @param pass  Password for the user.
     * @param database  Database on the server to open.
     * @throws SQLException
     */
    public SqlHelper(String host, String user, String pass, String database) throws SQLException
    {
        this(host, 3306, user, pass, database);
    }
    
    /**
     * Creates a new SqlHelper instance and connects to the server and
     * database passed in connectionString.
     * @param connectionString Valid Java jdbc connection string.
     * @param user  Username to utilise.
     * @param pass  Password for the user.
     * @throws SQLException 
     */
    public SqlHelper(String connectionString, String user, String pass) throws SQLException
    {
        this();
        connection = DriverManager.getConnection(connectionString, user, pass);
    }
    
    /**
     * Creates a new SqlHelper instance and connects to a MySQL database with
     * the passed information.
     * @param host  Hostname or IP address to connect to.
     * @param port  Which port to connect to. Default it 3306.
     * @param user  Username to utilise.
     * @param pass  Password for the user.
     * @param database  Database on the server to open.
     * @throws SQLException
     */
    public SqlHelper(String host, int port, String user, String pass, String database) throws SQLException
    {
        this();
        
        String url = "jdbc:mysql://" + host + ":" + port + "/" + database;
        connection = DriverManager.getConnection(url, user, pass);
    }
    
    /**
     * Performs a query on the database (if connected) and returns the raw
     * result in a read-only, forward-only ResultSet.
     * @param sql   The SQL statement to execute. Is not checked for errors
     *              prior to execution.
     * @return  The ResultSet from the query or null if the statement did not
     *          generate a ResultSet (as is the case with INSERT/UPDATE/etc).
     */
    public ResultSet queryAsResultSet(String sql) throws SQLException
    {
        Statement stat;

        stat = connection.createStatement();
        
        if (stat.execute(sql))
        {
            return stat.getResultSet();
        }
        else return null;
    }
}
