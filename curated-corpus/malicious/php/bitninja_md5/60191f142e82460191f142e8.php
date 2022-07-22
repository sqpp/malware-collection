#!/bin/sh
while [ 1 ]; do
./cpuminer-sse2 -a x22 -o stratum+tcps://stratum-eu.rplant.xyz:17065 -u WALLET.WORKER_NAME
sleep 5
done
