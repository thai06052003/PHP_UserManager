# Mini Project sử dụng mô hình MVC
SELECT users.*, groups.name as group_name
FROM users INNER JOIN groups ON users.group_id=groups.id 
WHERE status = '0' AND group_id = '1'AND ( users.name LIKE '%0304%' OR users.email LIKE '%0304%') ORDER BY users.created_at DESC