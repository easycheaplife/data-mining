# -*- coding:utf-8 -*-  
from __future__ import division
from numpy  import *
from sample  import sample
import json

#	step 0: sample class manually
check_flag = [
1,0,1,0,0,0,1,1,1,0,
0,0,0,0,1,1,1,1,0,1,
1,0,0,0,0,0,1,1,1,1,
1,1,1,0,0,1,1,1,0,0,
1,1,1,0,1,1,1,1,1,1,
0,0,1,0,0,1,0,1,0,0,
1,0,0,1,1,0,1,1,1,1,
1,0,1,1,1,0,1,0,0,0,
0,0,1,0,0,0,0,0,1,0,
1,1,1,1,0,0,1,1,0,1
]
sample_num = len(check_flag)

#	step 1: compute check or uncheck 's probability
print check_flag
check_num = 0
for v in check_flag:
  if v == 1:
	check_num = check_num + 1
print check_num

pcate1 = check_num / 100
pcate2 = (100 - check_num) / 100

print pcate1
print pcate2

#	step 2: extract all tags for check
def get_check_flag(index):
	return check_flag[index]
dicts = []
index = 0
for v in sample:
	for w in v:
	  flag = get_check_flag(index)
	  if 0 == flag:
		  continue
	  find = 0
	  for u in dicts:
	    if u == w:
		  find = 1
	  if 0 == find:
		dicts.append(w)
	index = index + 1

file = open('dicts.txt','w')
for v in dicts:
	file.write(v + '\n');
file.close()
print len(dicts)

#	test data
#check_flag = [1,1]

#	step 3:	come out tag's vector 
index = 0
tag_vector_cate1 = []
for m in check_flag:
	# cate1
	if m == 0:
		continue
	vect = []
	for u in dicts:
		find = 0
		for v in sample[index]: 
			res = v.count(u)
			if 1 <= res:
				find = 1
				break
			else:
				find = 0
			if 1 == find:
				break
		vect.append(find)
	tag_vector_cate1.append(vect)
	index = index + 1

index = 0
tag_vector_cate2 = []
for m in check_flag:
	# cate2
	if m == 1:
		continue
	vect = []
	for u in dicts:
		find = 0
		for v in sample[index]: 
			res = v.count(u)
			if 1 <= res:
				find = 1
				break
			else:
				find = 0
			if 1 == find:
				break
		vect.append(find)
	tag_vector_cate2.append(vect)
	index = index + 1

file1 = open('tag_vector_cate1.txt','w')
for v in tag_vector_cate1:
	for w in v:
		file1.write(str(w) + ' ')
	file1.write('\n')
#file1.write(json.dumps(tag_vector_cate1))
file1.close()

file2 = open('tag_vector_cate2.txt','w')
for v in tag_vector_cate2:
	for w in v:
		file2.write(str(w) + ' ')
	file2.write('\n')
#file2.write(json.dumps(tag_vector_cate2))
file2.close()

print len(tag_vector_cate1)
print len(tag_vector_cate2)


#	step 4: calc every tag 's probability
num_tag_cate1 = ones(len(dicts))
num_tag_cate2 = ones(len(dicts))
#print num_tag_cate1
#print num_tag_cate2
total_cate1 = 2.0
total_cate2 = 2.0
for item in tag_vector_cate1:
	num_tag_cate1 += item
	total_cate1 += sum(item)
p_tags_cate1 = num_tag_cate1 / total_cate1

for item in tag_vector_cate2:
	num_tag_cate2 += item
	total_cate2 += sum(item)
p_tags_cate2 = num_tag_cate2 / total_cate2
print p_tags_cate1
print p_tags_cate2




#############################################test################################################
from test_data  import test_data
file_result = open('bayes_class_result.txt',"w")
file_test_data = open('test_data.txt',"r")
all_test_data_lines = file_test_data.readlines()
file_test_data.close()

test_flag = []
for v in test_data:
	test_flag.append(1);

index = 0
for m in test_flag:
	tagsvect = []
	for u in dicts:
		find = 0
		for v in test_data[index]: 
			res = v.count(u)
			if 1 <= res:
				find = 1
				break
			else:
				find = 0
			if 1 == find:
				break
		tagsvect.append(find)
	#print tagsvect
	result_tags_cate1 = p_tags_cate1 * tagsvect	
	temp1 = 1.0
	for item in result_tags_cate1:
		if item != 0:
			temp1 = temp1 * item

	result_tags_cate2 = p_tags_cate2 * tagsvect	
	temp2 = 1.0
	for item in result_tags_cate2:
		if item != 0:
			temp2 = temp2 * item
	
	#	compare result 
	p_cate1_tags = temp1 * pcate1
	p_cate2_tags = temp2 * pcate2
	
	#	write file
	prefix = ""
	if p_cate1_tags > p_cate2_tags:
		print " p_cate1_tags= %s p_cate2_tags = %s check!" % (p_cate1_tags,p_cate2_tags)
		prefix = "需要检查---"
	else:
		print " p_cate1_tags= %s p_cate2_tags = %s not check!" % (p_cate1_tags,p_cate2_tags)
		prefix = "不需要检查---"
	file_result.write(prefix + all_test_data_lines[index])
	index = index + 1
	

