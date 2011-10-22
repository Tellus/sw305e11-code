package sw6.lib.girafplace;

import java.io.IOException;
import java.io.Serializable;

public class Application implements Serializable {
	/**
	 * 
	 */
	private static final long serialVersionUID = -8853669779399782858L;
	protected int id;
	protected String name;
	protected String description;
    protected String package_;
    protected int version;
    protected String versionString;
    protected State state;
    protected boolean canRead;
    protected boolean canDragAndDrop;
    protected boolean canHear;
    protected boolean requiresSimpleVisualEffects;
    protected boolean canAnalogTime;
    protected boolean canDigitalTime;
    protected boolean hasBadVision;
    protected boolean requiresLargeButtons;
    protected boolean canSpeak;
    protected boolean canNumbers;
    protected boolean canUseKeyboard;
    
    /**
     * A parameterized constructor for a new application.
     * @param id The ID of the application.
     * @param name The name of the application.
     * @param description The description of the application.
     * @param package_ The name of the package, the application is in.
     * @param version The version number of the application, as an integer.
     * @param versionString The version number of the application, as a string. E.g: "1.1".
     * @param state The state an application is in, in the GirafPlace.
     */
	public Application(int id, String name, String description, String package_, int version, String versionString, State state) {
        this.id = id;
        this.name = name;
        this.description = description;
        this.package_ = package_;
        this.version = version;
        this.versionString = versionString;
        this.state = state;
    }

	/**
	 * The getter for an application's ID.
	 * @return The ID of the application.
	 */
	public int getId() {
		return id;
	}

	/**
	 * The setter for an application's ID.
	 * @param id The ID of an application, to set.
	 */
	public void setId(int id) {
	    this.id = id;
	}
	
	/**
	 * The getter for an application's name.
	 * @return The name of an application.
	 */
	public String getName() {
		return name;
	}
	/**
	 * The setter for an application's name.
	 * @param name The name to set, for an application.
	 */
	public void setName(String name) {
		this.name = name;
	}
	/**
	 * The getter for an application's description.
	 * @return The description of an application.
	 */
	public String getDescription() {
		return description;
	}
	/**
	 * The setter of an application's description.
	 * @param description The description to set, for an application.
	 */
	public void setDescription(String description) {
		this.description = description;
	}
	
	/**
	 * The getter for the name of the package, an application is included in.
	 * @return The package name of an application.
	 */
	public String getPackage() {
	    return package_;
	}
	
	/**
	 * The setter for an application's package name.
	 * @param p The package name an application belongs to.
	 */
	public void setPackage(String p) {
	    this.package_ = p;
	}

	/**
	 * The getter for an appplication's version number.
	 * @return The version number of an application.
	 */
	public int getVersion() {
		return version;
	}

	/**
	 * The setter for an application's version number.
	 * @param version The version number to be set, on an application
	 */
	public void setVersion(int version) {
		this.version = version;
	}

	/**
	 * The getter for a string, containing the application's version number.
	 * @return The version number, as a string.
	 */
	public String getVersionString() {
		return versionString;
	}

	/**
	 * The setter for an application's version number, as a string.
	 * @param versionString The version number string to be set, on an application.
	 */
	public void setVersionString(String versionString) {
		this.versionString = versionString;
	}

	/**
	 * The getter for an application's state.
	 * @return The state of an application on GirafPlace.
	 */
	public State getState() {
		return state;
	}

	/**
	 * The setter for an application's state. (E.g: LIVE, ADDING, OLD, ERROR)
	 * @param state The state to set an application in. 
	 */
	public void setState(State state) {
		this.state = state;
	}

	/**
	 * Is responsible for writing the state of the object for its particular class 
	 * so that the corresponding readObject method can restore it, thus making it possible
	 * to write data over a network, through an ObjectOutputStream.
	 * @param out The application about to be sent over a network.
	 * @throws IOException
	 */
	private void writeObject(java.io.ObjectOutputStream out) throws IOException {
		 out.writeInt(this.id);
		 out.writeUTF(this.name);
		 out.writeUTF(this.description);
		 out.writeUTF(package_);
		 out.writeInt(this.version);
		 out.writeUTF(this.versionString);
		 out.writeBoolean(this.canAnalogTime);
		 out.writeBoolean(this.canDigitalTime);
		 out.writeBoolean(this.canDragAndDrop);
		 out.writeBoolean(this.canHear);
		 out.writeBoolean(this.canNumbers);
		 out.writeBoolean(this.canRead);
		 out.writeBoolean(this.canSpeak);
		 out.writeBoolean(this.canUseKeyboard);
		 out.writeBoolean(this.requiresLargeButtons);
		 out.writeBoolean(this.requiresSimpleVisualEffects);
		 out.writeBoolean(this.hasBadVision);
		 out.writeObject(state);
	 }
	/**
	* Is responsible for restoring the object that had been splitted by the writeObject
	* function, and thus makes it possible to read data over a network, through an ObjectInputStream.
	* 
	* @param in The application about to be received over a network.
	* @throws IOException 
	* @throws ClassNotFoundException 
	*/
	private void readObject(java.io.ObjectInputStream in) throws IOException, ClassNotFoundException {
		 this.setId(in.readInt());
		 this.setName((String)in.readUTF());
		 this.setDescription(in.readUTF());
		 this.setPackage(in.readUTF());
		 this.setVersion(in.readInt());
		 this.setVersionString(in.readUTF());
		 this.setCanAnalogTime(in.readBoolean());
		 this.setCanDigitalTime(in.readBoolean());
		 this.setCanDragAndDrop(in.readBoolean());
		 this.setCanHear(in.readBoolean());
		 this.setCanNumbers(in.readBoolean());
		 this.setCanRead(in.readBoolean());
		 this.setCanSpeak(in.readBoolean());
		 this.setCanUseKeyboard(in.readBoolean());
		 this.setRequiresLargeButtons(in.readBoolean());
		 this.setRequiresSimpleVisualEffects(in.readBoolean());
		 this.setHasBadVision(in.readBoolean());
		 this.setState((State)in.readObject());
	 }
	
	/**
	 * The getter for the canRead setting.
	 * @return The canRead boolean value. (0 = Cannot read, 1 = Can read.)
	 */
	public boolean isCanRead() {
		return canRead;
	}

	/**
	 * The setter for the canRead setting.
	 * @param canRead The canRead boolean value to set. (0 = Cannot read, 
	 * 1 = Can read.)
	 */
	public void setCanRead(boolean canRead) {
		this.canRead = canRead;
	}

	/**
	 * The getter for the canDragAndDrop setting.
	 * @return the canDragAndDrop boolean value. (0 = Cannot use drag and drop, 
	 * 1 = Can use drag and drop.)
	 */
	public boolean isCanDragAndDrop() {
		return canDragAndDrop;
	}

	/**
	 * The setter for the canDragAndDrop setting.
	 * @param canDragAndDrop The canDragAndDrop boolean value to set.
	 * (0 = Cannot use drag and drop, 1 = Can use drag and drop.)
	 */
	public void setCanDragAndDrop(boolean canDragAndDrop) {
		this.canDragAndDrop = canDragAndDrop;
	}

	/**
	 * The getter for the canHear setting.
	 * @return The canHear boolean value. (0 = Cannot hear, 1 = Can hear.)
	 */
	public boolean isCanHear() {
		return canHear;
	}

	/**
	 * The setter for the canHear setting.
	 * @param canHear The canHear boolean value to set.
	 * (0 = Cannot hear, 1 = Can hear.)
	 */
	public void setCanHear(boolean canHear) {
		this.canHear = canHear;
	}

	/**
	 * The getter for the requiresSimpleVisualEffects setting.
	 * @return The requiresSimpleVisualEffects boolean value.
	 * (0 = Does not require a simple interface, 1 = Requires a simple interface.)
	 */
	public boolean isRequiresSimpleVisualEffects() {
		return requiresSimpleVisualEffects;
	}

	/**
	 * The setter for the requiresSimpleVisualEffects setting.
	 * @param requiresSimpleVisualEffects The requiresSimpleVisualEffects 
	 * boolean value to set. (0 = Does not require a simple interface, 
	 * 1 = Requires a simple interface.)
	 */
	public void setRequiresSimpleVisualEffects(boolean requiresSimpleVisualEffects) {
		this.requiresSimpleVisualEffects = requiresSimpleVisualEffects;
	}

	/**
	 * The getter for the canAnalogTime setting.
	 * @return The canAnalogTime boolean value.
	 * (0 = Cannot read an analog clock, 1 = Can read an analog clock.)
	 */
	public boolean isCanAnalogTime() {
		return canAnalogTime;
	}

	/**
	 * The setter for the canAnalogTime setting.
	 * @param canAnalogTime The canAnalogTime boolean value to set.
	 * (0 = Cannot read an analog clock, 1 = Can read an analog clock.)
	 */
	public void setCanAnalogTime(boolean canAnalogTime) {
		this.canAnalogTime = canAnalogTime;
	}

	/**
	 * The getter for the canDigitalTime setting.
	 * @return The canDigitalTime boolean value.
	 * (0 = Cannot read an digital clock, 1 = Can read an digital clock.)
	 */
	public boolean isCanDigitalTime() {
		return canDigitalTime;
	}

	/**
	 * The setter for the canDigitalTime setting.
	 * @param canDigitalTime The canDigitalTime boolean value to set.
	 * (0 = Cannot read an digital clock, 1 = Can read an digital clock.)
	 */
	public void setCanDigitalTime(boolean canDigitalTime) {
		this.canDigitalTime = canDigitalTime;
	}

	/**
	 * The getter for the hasBadVision setting.
	 * @return The hasBadVision boolean value.
	 * (0 = Does not have a bad vision , 1 = Does have a bad vision.)
	 */
	public boolean isHasBadVision() {
		return hasBadVision;
	}

	/**
	 * The setter for the hasBadvision setting.
	 * @param hasBadVision The hasBadVision boolean value to set.
	 * (0 = Does not have a bad vision , 1 = Does have a bad vision.)
	 */
	public void setHasBadVision(boolean hasBadVision) {
		this.hasBadVision = hasBadVision;
	}

	/**
	 * The getter for the requiresLargeButtons setting.
	 * @return The requiresLargeButtons boolean value.
	 * (0 = Does not require larger buttons , 1 = Does require larger buttons.)
	 */
	public boolean isRequiresLargeButtons() {
		return requiresLargeButtons;
	}

	/**
	 * The setter for the requiresLargeButtons setting.
	 * @param requiresLargeButtons The requiresLargeButtons boolean value to set.
	 * (0 = Does not require larger buttons , 1 = Does require larger buttons.)
	 */
	public void setRequiresLargeButtons(boolean requiresLargeButtons) {
		this.requiresLargeButtons = requiresLargeButtons;
	}

	/**
	 * The getter for the canSpeak setting.
	 * @return The canSpeak boolean value.
	 * (0 = Is not able to speak simple sentences, 
	 * 1 = Is able to speak simple sentences.)
	 */
	public boolean isCanSpeak() {
		return canSpeak;
	}

	/**
	 * The setter for the canSpeak setting.
	 * @param canSpeak The canSpeak boolean value to set.
	 * (0 = Is not able to speak simple sentences, 
	 * 1 = Is able to speak simple sentences.)
	 */
	public void setCanSpeak(boolean canSpeak) {
		this.canSpeak = canSpeak;
	}

	/**
	 * The getter for the canNumbers setting.
	 * @return The canNumbers boolean value.
	 * (0 = Is not able to count numbers, 1 = Is able to count numbers.)
	 */
	public boolean isCanNumbers() {
		return canNumbers;
	}

	/**
	 * The setter for the canNumbers setting.
	 * @param canNumbers The canNumbers boolean value to set.
	 * (0 = Is not able to count numbers, 1 = Is able to count numbers.)
	 */
	public void setCanNumbers(boolean canNumbers) {
		this.canNumbers = canNumbers;
	}

	/**
	 * The getter for the canUseKeyboard setting.
	 * @return The canUseKeyboard boolean value.
	 * (0 = Is not able to use the standard keyboard on the device, 
	 * 1 = Is able to use the standard keyboard on the device.)
	 */
	public boolean isCanUseKeyboard() {
		return canUseKeyboard;
	}

	/**
	 * The setter for the canUseKeyboard setting.
	 * @param canUseKeyboard The canUseKeyboard boolean value to set.
	 * (0 = Is not able to use the standard keyboard on the device, 
	 * 1 = Is able to use the standard keyboard on the device.)
	 */
	public void setCanUseKeyboard(boolean canUseKeyboard) {
		this.canUseKeyboard = canUseKeyboard;
	}
}