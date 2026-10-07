const PB={'Electrical':3,'Network/Internet':3,'Water':3,'Computer/Lab':2,'Plumbing':2,'AC/Fan':2,'Classroom':2},SV={Low:0,Medium:1,High:3};
function calcPriority(c,s){const t=(PB[c]||1)+(SV[s]||0);return t>=5?'HIGH':t>=3?'MEDIUM':'LOW'}
document.addEventListener('DOMContentLoaded',()=>{
 const f=document.getElementById('complaintForm');if(!f)return;
 const out=document.getElementById('prioPreview'),upd=()=>{out.textContent=f.category.value&&f.severity.value?calcPriority(f.category.value,f.severity.value):'--'};
 f.category.addEventListener('change',upd);f.severity.addEventListener('change',upd);
 f.addEventListener('submit',e=>{let ok=true;
  f.querySelectorAll('[required]').forEach(i=>{if(!i.value.trim()){i.classList.add('is-invalid');ok=false}else i.classList.remove('is-invalid')});
  if(f.description.value.trim().length<10){f.description.classList.add('is-invalid');ok=false}
  const im=f.image.files[0];if(im&&(!/\.(jpe?g|png)$/i.test(im.name)||im.size>2097152)){alert('Image must be JPG/PNG under 2MB');ok=false}
  if(!ok)e.preventDefault()});});
