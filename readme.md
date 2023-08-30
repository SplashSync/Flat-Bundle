
[![N|Solid](https://github.com/SplashSync/Php-Core/raw/master/img/github.jpg)](https://www.splashsync.com)

# Splash Flat Connector 
Base Connector for parsing flat files from remote locations:

# What's done for ?
This connector may be used to connect flat data sources as Splash Objects.

A flat source could be one or multiple csv|xml files, located on a remote server, 
that have to be connected and used bay Splash Sync as generic objects. 

# How it works
Reading data from source always follow the same process: 
1. **The File Reader** read raw file from a predefined location.
2. **The File Parser** convert file contents into an associative array of objects raw data.
3. **The Formater** (Optional) decode, treat and convert objects raw data
4. **The Hydrator** convert objects raw data into Object Models

Once list of all objects is collected, Flat connector uses models data to read, 
compare & export data to Splash Sync server.

For a better performance, parsed objects list is stored on cache. 

# The Adapter
The Adapter only used to read file contents from a given location
- Local files via full path
- Remote files via url
- FTP / SFTP files via a connection string

# The Parser
* **The Adapter** read static file & parse it onto an array of objects data
* **The Adapter** read static file & parse it onto an array of objects data
* **The Formater** (Optional) Decode, convert, parse objects data
* 

