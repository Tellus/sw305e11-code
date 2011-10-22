package sw3.lib.girafweb;

import java.sql.Timestamp;

/**
 * Represents a device as they are found in the GirafWeb database (default
 * table is "devices"). Due to table and design restrictions, you cannot update
 * a device's ident (it never changes for one device), it's id (auto-generated)
 * or its timestamp (always automatically updated). Only the owner can change.
 * @author Johannes Lindhart Borresen
 */
public class Device
{
    protected int id;
    protected int ownerId;
    protected String ident;
    protected Timestamp lastUpdated;
    
    public Device(int newId, int owner, String newIdent, Timestamp updateTime)
    {
        id = newId;
        ownerId = owner;
        ident = newIdent;
        lastUpdated = updateTime;
    }
    
    public int getId() {return id;}
    
    public int getOwnerId() { return ownerId;}
    
    public String getIdent() {return ident;}
    
    public Timestamp getLastUpdateTime() {return lastUpdated;}
    
    public void setOwnerId(int newId) { ownerId = newId; }
}
