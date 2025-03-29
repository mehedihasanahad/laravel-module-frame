import axios from "axios";

const BaseURL=process.env.MIX_PROJECT_ROOT +"/api";


export async function AllDivision(){
    let res=await axios.get(BaseURL+"/get-division");

    if (res.status==200){
        return res.data;
    }else{
        return[];
    }
}

export async function Districts(id){
    let res=await axios.get(BaseURL+"/v1/districts/" +id);

    if (res.status==200){
        return res.data;
    }else{
        return[];
    }
}

export async function Upzila(id){
    let res=await axios.get(BaseURL+"/v1/upzilas/" +id);

    if (res.status==200){
        return res.data;
    }else{
        return[];
    }
}