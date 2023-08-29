  const ytkey="AIzaSyCRE3otvaEG19VygJn97GvuKceNhYiN_hE";
  let urlRaw = "";
  let ytId="";
  let channelId="";

  var vid = "rM82RU9Yg88",
  audio_streams = {},
  audio_tag = document.getElementById('youtube');



function parse_str(str) {
    return str.split('&').reduce(function(params, param) {
        var paramSplit = param.split('=').map(function(value) {
            return decodeURIComponent(value.replace('+', ' '));
        });
        params[paramSplit[0]] = paramSplit[1];
        return params;
    }, {});
}





  function getId(urlRaw){
    let ok = false;
    let i = 0;  
    while(urlRaw[i]!=null){

    
        if(urlRaw[i]=='='){
        ok = true;
        for(let j=0;j<11;j++){
            ytId+=urlRaw[++i];
        }
        i = 100;
        }

      i++;
    }

  }

  function makeRatio(a,b){
    a = parseInt(a);
    b = parseInt(b);
    let sum = a+b;
    let likes = (a/sum) * 100;
    console.log(likes);
    return likes;

  }


  function roundToK(x){
    x = parseInt(x);
    let cop = x;
    let nou;
    let i = 0;
    while(x>0){
        i++;
        x = parseInt(x/10);
    }
    console.log("i= "+i);
    if(i>3 && i<=6){
        cop=parseInt(cop/1000);
        nou = cop+" K";
    }
    else if(i>6){
        cop=parseInt(cop/1000000);
        nou = cop+" M";
    }
    else{
        nou = cop;
    }
    return nou;


  }

  let GetAndReplaceVideoInfo=()=>{
    getId(urlRaw);

    let url = 'https://youtube.googleapis.com/youtube/v3/videos?part=snippet&id=' + ytId + '&key=' + ytkey;
    fetch(url)
      .then(response=>{
        //console.log(response);
        return response.json();
      })
      .then(data=>{
        let snippet = data["items"][0].snippet;
        console.log(snippet);
        // inlocuim cu info
        let upload = snippet.publishedAt;
        upload = upload.substring(0,10);
        document.getElementById('upload-time').innerHTML = upload;
        document.getElementById('title').innerHTML = snippet.title;
        document.getElementById('thumbnail').src = snippet.thumbnails.high.url;
        document.getElementById('upload-author').innerHTML = snippet.channelTitle;
        document.getElementById('more').innerHTML = "More about " + snippet.channelTitle + " channel:";
        channelId = snippet.channelId;

        GetAndReplaceVideoStatistics();
      })
  }

  let GetAndReplaceVideoStatistics=()=>{


    let url = 'https://youtube.googleapis.com/youtube/v3/videos?part=statistics&id=' + ytId + '&key=' + ytkey;
    fetch(url)
      .then(response=>{
        //console.log(response);
        return response.json();
      })
      .then(data=>{
        let statistics = data["items"][0].statistics;
        console.log(statistics);
        // inlocuim cu info
        document.getElementById('likes').innerHTML = roundToK(statistics.likeCount);
        document.getElementById('dislikes').innerHTML = roundToK(statistics.dislikeCount);
        document.getElementById('ratio').innerHTML = parseInt(makeRatio(statistics.likeCount, statistics.dislikeCount)) + "% likes";

        GetAndReplaceChannelInfo();
    })
  }

  let GetAndReplaceChannelInfo=()=>{

    let url = 'https://youtube.googleapis.com/youtube/v3/channels?part=statistics&id=' + channelId + '&key=' + ytkey;
    fetch(url)
      .then(response=>{
        //console.log(response);
        return response.json();
      })
      .then(data=>{
        console.log(data);
        let info = data["items"][0].statistics;
        console.log(info);
        document.getElementById('subscribers').innerHTML = "Subscribers: " + roundToK(info.subscriberCount);
        document.getElementById('total_views').innerHTML = "Channel views: " + roundToK(info.viewCount);
        document.getElementById('total_videos').innerHTML = "Channel videos: " + info.videoCount;
        document.getElementById('ratio_views').innerHTML = "Average views per video: " + roundToK(parseInt(info.viewCount/info.videoCount));

        GetAndReplaceChannelAvatar();
    })
  }

  let GetAndReplaceChannelAvatar=()=>{

    let url = 'https://youtube.googleapis.com/youtube/v3/channels?part=snippet&id=' + channelId + '&key=' + ytkey;
    fetch(url)
      .then(response=>{
        //console.log(response);
        return response.json();
      })
      .then(data=>{
        console.log(data);
        let info = data["items"][0].snippet;
        document.getElementById('avatarcanal').src = info.thumbnails.high.url;
      
        GetDataVideoComments();
    })
  }

  let GetDataVideoComments=()=>{

    let url = 'https://youtube.googleapis.com/youtube/v3/commentThreads?part=snippet%2Creplies&order=relevance&videoId=' + ytId + '&key=' + ytkey;
    fetch(url)
      .then(response=>{
        return response.json();
      })
      .then(data=>{
        let info = data.items[0].snippet.topLevelComment.snippet;
        document.getElementById("comm_avatar").src = info.authorProfileImageUrl;
        document.getElementById("comm_author").innerHTML = info.authorDisplayName;
        document.getElementById("comm_text").innerHTML = info.textDisplay;
        document.getElementById("comm_likes").innerHTML = roundToK(info.likeCount);
        

      })
      .catch(response=>{
        console.log("ufff");
        document.getElementById("comm_author").innerHTML = "Comments are turned off. ";
        document.getElementById("comm_text").innerHTML = "";
        document.getElementById("comm_likes").innerHTML = "";
        document.getElementById("like1").style.display = "none";





      })
  }


  function showButt(){
    urlRaw = document.getElementById("inp_text").value;
    console.log(urlRaw);
    if(urlRaw!=null && urlRaw.length>10){
        document.getElementById("content").style.display="block";
        document.getElementById("inp_text").style.display="none";
        document.getElementById("inp_butt").style.display="none";

        GetAndReplaceVideoInfo();
    }
  }

  
