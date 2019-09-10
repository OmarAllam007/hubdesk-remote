SELECT
    ti.id 'Hubdesk ID',
    c.name 'Category',
    sub.name 'SubCategory',
    user.name 'Requester',
    user.employee_id 'Requester SAP ID',
    bu.name 'Company',
    tech.name 'Technician',
    st.name 'Status',
    DATE_FORMAT(ti.created_at, '%d/%m/%Y ') 'Received Date',
    DATE_FORMAT(ti.due_date, '%d/%m/%Y ') 'Due (Action) Date',
    DATE_FORMAT(ti.resolve_date, '%d/%m/%Y ') 'Closed Date',
    (SELECT DATE_FORMAT(r.created_at, '%d/%m/%Y')
     FROM ticket_replies r
     WHERE r.status_id IN (5, 4) AND r.user_id = ti.technician_id AND r.ticket_id = ti.id
     ORDER BY DATE_FORMAT(r.created_at, '%d/%m/%Y') DESC
     LIMIT 1) 'Requirement Date',

    (SELECT DATE_FORMAT(r.created_at, '%d/%m/%Y %h:%m')
     FROM ticket_replies r
     WHERE r.status_id = 1 AND r.user_id = ti.requester_id AND r.ticket_id = ti.id
     ORDER BY DATE_FORMAT(r.created_at, '%d/%m/%Y ') DESC
     LIMIT 1) 'Feedback Date',
    (SELECT tf.value FROM ticket_fields tf WHERE ti.id = tf.ticket_id AND tf.name LIKE '%Employee%' LIMIT 1) 'Employee ID'
FROM tickets ti
         LEFT JOIN categories c ON ti.category_id = c.id
         LEFT JOIN subcategories sub ON ti.subcategory_id = sub.id
         LEFT JOIN users tech ON ti.technician_id = tech.id
         LEFT JOIN users user ON ti.requester_id = user.id
         LEFT JOIN statuses st ON ti.status_id = st.id
         LEFT JOIN business_units bu ON bu.id = user.business_unit_id
WHERE ti.created_at BETWEEN '2019-08-01 00:00:00' AND '2019-08-31 11:59:59' AND ti.category_id = 31
# and ti.status_id IN (1,2,3)