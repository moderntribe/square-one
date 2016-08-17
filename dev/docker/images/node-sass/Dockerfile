FROM alpine:3.3
MAINTAINER Modern Tribe

RUN apk add --no-cache python=2.7.12-r0 curl git wget git-perl bash make gcc g++ linux-headers libgcc libstdc++
RUN rm /bin/sh && ln -s /bin/bash /bin/sh

### install node version from source ###
### substitute the 4 in `make -j4` for the number of cores your docker has
RUN cd /tmp && \
    wget https://iojs.org/dist/v1.8.4/iojs-v1.8.4.tar.gz && \
    tar -xzvf iojs-v1.8.4.tar.gz &&\
    cd iojs-v1.8.4 && \
    ./configure &&\
    make -j4 &&\
    make install &&\
    node -v

RUN git clone https://github.com/sass/node-sass --recursive
WORKDIR /node-sass
RUN rm -rf vendor
RUN git fetch
RUN git checkout v3.7.0
RUN git submodule update --init --recursive
RUN npm install --verbose
RUN node scripts/build.js -f --verbose

RUN ls -lsa vendor
RUN tar -cvzf binaries.tar.gz vendor/

RUN npm install -g bower grunt-cli && \
    echo '{ "allow_root": true }' > /root/.bowerrc

WORKDIR /root/

RUN apk add --update ruby
RUN gem install sass

RUN apk add --update openssh-client
