current_dir=$(pwd)
SCRIPT_PATH=`dirname "$0"`
cd  $SCRIPT_PATH

echo $! > pid_file
./utils/infAuthFlow.sh | ./utils/flow2inMes.php auth |./utils/inMes2outMes.php |./utils//sendToQueue.php 
cd $current_dir