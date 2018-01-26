SELECT ac.aseq,ap.no,posted_date_time,acc,amount*dir,quantity_purchased,order_id,order_item_code,'AMZ', 
if((acc='abank_memo' AND ap.amount_description='Payable to Amazon') OR (acc='abank_memo' AND ap.amount_description='Successful charge'), 
concat(right( EXTRACT(YEAR_MONTH FROM posted_date + interval 4 day),4), date_format(posted_date + interval 4 day, '%d')) , settlement_id ) clearing,ttype, fromdoc, ap.ba,concat(ap.transaction_type,'-',ap.amount_type,'-',ap.amount_description) remark, a.brand,a.matid,now(),now()


SELECT
acc,
if((acc='ainv' or acc='pcgs'),
m.map*quantity_purchased*dir,
amount ) amt,
quantity_purchased,order_id,ap.sku,a.brand,a.matid
, m.map
FROM apay2 ap
JOIN apay2_acc aa 
ON ap.transaction_type = aa.transaction_type AND ap.amount_type = aa.amount_type AND ap.amount_description = aa.amount_description
LEFT JOIN ainv a ON a.sku=ap.sku
LEFT JOIN mat m ON m.vendor=a.brand AND m.matid=a.matid
WHERE 
fromdoc='apay2' 
AND aa.transaction_type = 'other-transaction' 
AND aa.amount_type = 'FBA Inventory Reimbu' 
AND posted_date_time >= '2017-01-01' 
AND posted_date_time < '2018-01-01'
LIMIT 10;


AND aa.amount_description = 'WAREHOUSE_DAMAGE' 
ap.postingflag IS NOT NULL 
AND 


SELECT ap.transaction_type, ap.amount_type, ap.amount_description,acc, if((acc='ainv' or acc='pcgs'), m.map*quantity_purchased*dir, amount ) amt, quantity_purchased,order_id,ap.sku,a.brand,a.matid , m.map 
FROM apay2 ap 
JOIN apay2_acc aa ON ap.transaction_type = aa.transaction_type AND ap.amount_type = aa.amount_type AND ap.amount_description = aa.amount_description 
WHERE  fromdoc='apay2'  AND aa.acc like 'psales%'