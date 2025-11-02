import sys
try:
    import yaml
except Exception as e:
    print('PyYAML not available:', e)
    sys.exit(2)

path = 'storage/api-docs/api-docs.yaml'
try:
    with open(path, 'r', encoding='utf-8') as f:
        yaml.safe_load(f)
    print('YAML parsed successfully')
except Exception as e:
    print('Parse error:', e)
    import traceback
    traceback.print_exc()
    sys.exit(1)
