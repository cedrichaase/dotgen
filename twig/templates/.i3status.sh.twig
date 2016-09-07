#!/bin/bash

function append {
  line="$1 | $line"
}

i3status | while true; do
  read line

  if uptime=$(uptime | grep -Po 'up\s*\d+\:?\d*\s*\w*'); then
    append "$uptime"
  fi

  if playing=$(mpc | head -1 | grep "-"); then
    append $playing
  fi

  echo $line
done
