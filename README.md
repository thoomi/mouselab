# Mouselab
Simple mouselab survey with time tracking using an Angular frontend. It connects to a Slim backend and stores the data in a mysql database.

A running version of the system can be found at http://surveyt.stephan-kopietz.de.

## Installation
**Backend and Api:** Upload the files to a server and install the dependencies with

    composer install
    
**Frontend:** For local development cd into the frontend folder and run

    npm install
    bower install
    
followed by a 

    grunt build 
or

    grunt serve
    
to either build a new version into the dist folder or pop up a livereload development server.


## TODO
- Add simple frontend to the data backend (currently only csv export)

## Questions?
If you have any questions regarding something other than the technical implementation, do not hestiate to send stephan.kopietz@gmx.de an e-mail. :)
