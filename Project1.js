function displayPopup(info){
    
    document.getElementById('pop').style.display='block';
    document.getElementById('bg').style.display='block';
    document.getElementById("name").textContent = 'Product name:' + info.name;
    document.getElementById("price").textContent = 'Price: $' + info.price;
    document.getElementById("stat").textContent = 'Status:' + info.stat;
    document.getElementById("toolId").textContent = 'ID:' + info.id;
    document.getElementById("desc1").textContent = 'Description:' + info.desc;

}

function closePopup(){
    document.getElementById('pop').style.display='none';
    document.getElementById('bg').style.display='none';
    
}