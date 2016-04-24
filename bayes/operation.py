from numpy import *
from bayes import *
from operation_sample import sample,classes
from weibo import doc 
import json
#print classes

def training():
	myVocabList = createVocabList(sample)
	trainMat = []
	for postinDoc in sample:
		trainMat.append(setOfWords2Vec(myVocabList,postinDoc))
	p0v,p1v,pAb = trainNB0(array(trainMat),array(classes))
	return p0v,p1v,pAb


p0v,p1v,pAb =  training()
print p0v,p1v,pAb
myVocabList = createVocabList(sample)
index = 0
weibo_detail_file = open('weibo_detail.txt',"r")
weibo_result_file = open('weibo_result.txt',"w+")
all_weibo_detail_lines = weibo_detail_file.readlines()
weibo_detail_file.close()

classified1 = 0
classified2 = 0
for v in doc:
	thisDoc = array(setOfWords2Vec(myVocabList,v))
	classified = classifyNB(thisDoc,p0v,p1v,pAb)
	if 1 == classified:
		classified1 = classified1 + 1
		weibo_result_file.write(all_weibo_detail_lines[index])
	else:
		classified2 = classified2 + 1
	#print all_weibo_detail_lines[index],'classified as : ' ,classified
	index = index + 1

print classified1 
print classified2

weibo_detail_file.close()
weibo_result_file.close()
