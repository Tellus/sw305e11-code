package sw6.lib.girafplace;

/**
 * The enumeration types being used to describe 
 * an application's state, in GirafPlace.
 * (ADDING = The application is being uploaded to GirafPlace,
 * LIVE = The application has passed the criteria in order to 
 * be shown in GirafPlace and is now visible,
 * OLD = The application is out of date as a newer version 
 * of the application has been uploaded,
 * ERROR = An error has occurred while uploading the application.
 * NOTE: The application is still uploaded to GirafPlace but is 
 * not visible in GirafPlace on the device.)
 * @author SW6C
 */
public enum State {
	LIVE,ADDING,OLD,ERROR;
}
