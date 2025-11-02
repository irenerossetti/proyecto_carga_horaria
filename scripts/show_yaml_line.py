import sys
path='storage/api-docs/api-docs.yaml'
start=1250
end=1265
with open(path,'r',encoding='utf-8') as f:
    lines=f.readlines()
for i in range(start-1, min(end, len(lines))):
    line=lines[i].rstrip('\n')
    print(f"{i+1:5}: {line!r}")
    # print column indices below
    print('      '+''.join([str((j+1)%10) for j in range(len(line))]))
