package sw3.server;

/**
 * Contains constants denoting various global,
 * parameterless actions required after a server
 * loop - in particular, after the scanner phase.
 * @author Johannes L. Borresen
 *
 */
public enum CommandOpEnum
{
    NOOP, // Used to denote that no particular action is necessary.
    TERM,   // Term signal, making the server shut down on its next iteration.
    NEWCONF, // Signifies that a new config file has been created. Causes term to force configuration.
    STARTUPERR // Signifies a severe error during startup. The server will shut down.
}
