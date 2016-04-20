current_dir=$(pwd)
SCRIPT_PATH=`dirname "$0"`
cd  $SCRIPT_PATH
file="./pid_file"
if [ -f "$file" ]
then
pid=$(<$file)
    echo "$file $var process already run."
else 
    echo $! > $file
    ./utils/infAuthFlow.sh | ./utils/flow2inMes.php auth |./utils/inMes2outMes.php |./utils//sendToQueue.php 
    rm $file
fi
cd $current_dir