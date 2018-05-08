FROM phusion/baseimage:0.9.18

RUN echo 'deb http://s3-eu-west-1.amazonaws.com/qafoo-profiler/packages debian main' > /etc/apt/sources.list.d/tideways.list && \
    curl -sS 'https://s3-eu-west-1.amazonaws.com/qafoo-profiler/packages/EEB5E8F4.gpg' | sudo apt-key add - && \
    apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get -yq install tideways-daemon && \
    apt-get autoremove --assume-yes && \
    apt-get clean && chmod +x run.sh && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ADD run.sh /run.sh

CMD ["/run.sh"]