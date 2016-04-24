#/bin/sh
./lda  -est -alpha 0.5 -beta 0.1 -ntopics 100 -niters 1000 -savestep 100 -twords 20 -dfile ../../data/specialize.dat 
./lda -estc -dir ../../data/ -model model-01000 -niters 800 -savestep 100 -twords 30
./lda -inf -dir ../../data/ -model model-01800 -niters 30 -twords 20 -dfile newintro.dat
