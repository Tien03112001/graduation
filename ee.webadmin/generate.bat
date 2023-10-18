@ECHO OFF
CALL gulp copyModule --basePath=./src/app/modules --sourceModule=%1 --destinationModule=%2
