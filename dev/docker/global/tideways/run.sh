#!/bin/bash

tideways-daemon --hostname=tideways-daemon --address=0.0.0.0:9135 --udp=0.0.0.0:8135 >> /dev/stdout 2>/dev/stderr

/sbin/my_init