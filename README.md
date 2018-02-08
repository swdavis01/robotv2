# Robot Simulator

## Description  
- The application is a simulation of a toy robot moving on a square tabletop, of dimensions 5 units x 5 units.
- There are no other obstructions on the table surface.
- The robot is free to roam around the surface of the table, but must be prevented from falling to destruction. Any movement
that would result in the robot falling from the table must be prevented, however further valid movement commands must still
be allowed.

The application is written in PHP and is a composer package.

## Main Files
The main log files are:  
src/Command/ControlCommand.php (defines the console command)  
src/Services/RobotService.php (the main logic of the application)  
Data objects are stored in:  
src/Entity

## Installation
Requires at PHP 5.3.0 or above.  
On Debian / Ubuntu:  
```
sudo apt-get install php[5,7]
```



## Running

```
src/Command/Console.php robot:control PLACE
```
