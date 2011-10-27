package sw3.lib.girafweb;

import java.util.HashMap;
import java.util.Map;

/**
 * A cloud-targetted derivative of the sw6.lib Profile class, Profile
 * changes focus to run cached like a data container, currently being without
 * any autonomous methods on its own. It is meant to contain data about a single
 * autistic person with a device
 * @author Johannes Lindhart Borresen.
 */
public class Profile
{
    protected Map<String, Boolean> _abilities;
    
    protected String _name;
    
    protected int _age;
    
    /**
     * Set the profile's age.
     * @param age New age of the profile.
     */
    public void setAge(int age){_age = age;}
    
    /**
     * Sets the name on the profile.
     * @param name New name on the profile.
     */
    public void setName(String name){_name = name;}
    
    /**
     * Gets the profile's current age.
     * @return
     */
    public int getAge(){return _age;}
    
    /**
     * Gets the profile's name.
     * @return  
     */
    public String getName(){return _name;}
    
    protected int _id;
    
    public int getId(){return _id;}
    
    /**
     * Retrieves the value of a single ability.
     * @param abilityName   The name of the ability to retrieve. All abilities
     * are defined by default in the GirafWeb table "abilityDefinitions".
     * @return Returns true if the profile has the ability and it is set to
     * true, false otherwise.
     */
    public Boolean getAbility(String abilityName)
    {
        if (!_abilities.containsKey(abilityName)) return false;
        else return _abilities.get(abilityName);
    }
    
    /**
     * Sets (or re-sets) a specific ability value for the profile. Any false
     * values will either be ignored by the database or result in deletions of
     * the appropriate rows. False abilities are never saved, to conserve space.
     * @param abilityName   The name of the ability to set (see GirafWeb table "abilityDefinitions").
     * @param value The new value for the ability.
     */
    public void setAbility(String abilityName, Boolean value)
    {
        _abilities.put(abilityName, value);
    }
    
    /**
     * Generates a *copy* of the profile's abilities. Note that you are unable
     * to modify the profile's abilities with this result.
     * @return  A copy of the profile's _abilities map.
     */
    public Map<String, Boolean> getAbilities()
    {
        HashMap<String, Boolean> ret = new HashMap<String, Boolean>();
        ret.putAll(_abilities);
        
        return ret;
    }
    
    /**
     * Creates a new, empty, profile instance.
     */
    public Profile()
    {
        //_abilities = new ArrayList<Boolean>();
        _abilities = new HashMap<String, Boolean>();
    }
    
    /**
     * Creates a new, empty, Profile instance with an Id. Recall they cannot
     * be changed... for some reason. We'll debate that.
     * @param id
     */
    public Profile(int id)
    {
        _id = id;
    }
}
