# Robot Simulator

## Description  
- The application is a simulation of a toy robot moving on a square tabletop, of dimensions 5 units x 5 units.
- There are no other obstructions on the table surface.
- The robot is free to roam around the surface of the table, but must be prevented from falling to destruction. Any movement
that would result in the robot falling from the table must be prevented, however further valid movement commands must still
be allowed.

The application is written in PHP and is a composer package. It is a command line tool that makes use of the Symfony Console Command library.

A screen shot of how the application should look when running is provided in the images folder.

## Main Files
The main files are:  
src/Command/ControlCommand.php (defines the console command)  
src/Services/RobotService.php (the main logic of the application)  
Data objects are stored in:  
src/Entity

## Installation
Requires PHP 5.3.0 or above.  
On Debian / Ubuntu:  
```
sudo apt-get install php[5,7]
```

Extract the archive provided then:  
```
cd robot/
```

## Running
View console help / instructions
```
src/Command/Console.php robot:control --help
```

Commands are separated by the | symbol.   
Data format is ACTION,FACING,X,Y|ACTION|ACTION|ACTION.   
ACTION options are:  
- PLACE (Must also include FACING,X,Y e.g. PLACE,NORTH,2,5), 
- MOVE (will move the toy robot one unit forward in the direction it is currently facing), 
- LEFT (will rotate the robot 90 degrees to the left without changing the position of the robot), 
- RIGHT (will rotate the robot 90 degrees to the right without changing the position of the robot)
Actions can be in any order.

Sample command:  
```
src/Command/Console.php robot:control 'MOVE|LEFT|PLACE,SOUTH,0,2|REPORT|MOVE|REPORT|MOVE|REPORT|LEFT|MOVE|REPORT|RIGHT|MOVE|RIGHT|MOVE|REPORT|RIGHT|MOVE|REPORT'
```
Sample command to traverse the grid:  
```
src/Command/Console.php robot:control 'TRAVERSE_GRID'
```
