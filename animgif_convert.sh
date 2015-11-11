#!/bin/sh

find image/android -name "*.gif" | sed -r 's/.gif$//g' | xargs -I {file} ffmpeg -i {file}.gif -an -vcodec libvpx -f webm {file}.webm
find image/android -name "*.gif" | sed -r 's/.gif$//g' | xargs -I {file} ffmpeg -i {file}.gif -an -vcodec libx264 -pix_fmt yuv420p -profile:v main -level 31 -f mp4 {file}.mp4
find image/android -name "*.gif" | sed -r 's/.gif$//g' | xargs -I {file} ffmpeg -i {file}.gif -vframes 1 {file}.jpeg
