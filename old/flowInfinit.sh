interupted=false
int_handler()
{
    pkill -P $$
    echo "Interrupted."
    # Kill the parent process of the script.
    kill $PPID
    interupted=true
    exit 1
}

trap 'int_handler' INT


while :
do
    timeout 10 tail -n 0 --silent -f /onyma/ap/unis/logs/auth*
    if interupted ; then exit 1; fi
done
