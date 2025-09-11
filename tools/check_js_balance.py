import re
from pathlib import Path
p = Path(r"c:\xampp\htdocs\app_toppdv\app\Views\vendas.php")
s = p.read_text(encoding='utf-8')
# extract first <script>...</script> block
m = re.search(r"<script[^>]*>([\s\S]*?)</script>", s, re.IGNORECASE)
if not m:
    print('NO_SCRIPT_BLOCK')
    raise SystemExit(0)
script = m.group(1)
print('SCRIPT_LENGTH:', len(script))
# simple stack-based checker
pairs = {'(': ')', '[': ']', '{': '}' }
opens = set(pairs.keys())
closes = set(pairs.values())
stack = []
quotes = None
backtick = False
line = 1
errors = []
for i,ch in enumerate(script):
    if ch == '\n':
        line += 1
    # if inside single/double quote, only escape with backslash
    if quotes:
        if ch == '\\':
            # skip next char
            i += 1
            continue
        if ch == quotes:
            quotes = None
        continue
    if backtick:
        if ch == '`':
            backtick = False
        elif ch == '\\':
            i += 1
        continue
    # not inside quote
    if ch == '"' or ch == "'":
        quotes = ch
        continue
    if ch == '`':
        backtick = True
        continue
    if ch in opens:
        stack.append((ch,line,i))
    elif ch in closes:
        if not stack:
            errors.append((line, 'unmatched close', ch, i))
        else:
            last, lln, pos = stack[-1]
            if pairs[last] == ch:
                stack.pop()
            else:
                errors.append((line, 'mismatch', last, ch, i))
# report
if stack:
    print('UNMATCHED_OPEN_COUNT', len(stack))
    for ch,line,pos in stack:
        print('OPEN', ch, 'at line', line)
else:
    print('NO_UNMATCHED_OPENS')
if errors:
    print('ERRORS_FOUND')
    for e in errors[:10]:
        print(e)
else:
    print('NO_ERRORS_FOUND')
# print last 5 lines of script for inspection
lines = script.splitlines()
print('---LAST 20 LINES OF SCRIPT---')
for ln in lines[-20:]:
    print(ln)

# also search for any bare characters after </script> in file
post = s[m.end():]
if post.strip():
    print('AFTER_SCRIPT_NONEMPTY')
    print('TAIL:', repr(post[:200]))
else:
    print('AFTER_SCRIPT_EMPTY')
