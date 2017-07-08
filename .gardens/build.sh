#!/bin/bash
# you modify this to suit the needs of your project, whatever needs to happen to build your project

npm set progress=false
npm install --production &> /dev/null
grunt