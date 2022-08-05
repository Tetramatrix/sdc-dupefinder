//const $ = window.$;
const { Component, render } = wp.element;
//import React, { Component } from 'react';
import axios from 'axios';
//import { Link } from 'react-router-dom';
import { Spinner } from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import '../src/fx.css';

class PostList extends Component {
	
  constructor(props) {
    super(props);
    this.state = {
    	btnText : "Load More...",
    	isLoaded : false,
      start : 0,
      limit : scd_dupefinder_obj.posts_per_page,
      posts: [],           
    };
    this.handleSubmit = this.handleSubmit.bind(this);
    this.createMarkup = this.createMarkup.bind();
  }
  
  componentDidUpdate() {
  	 //debugger;
  	 //window.jQuery("#scd_dupefinder .scd_post").css('height', window.jQuery('#scd_dupefinder .scd_post').height());
  	 window.jQuery("#scd_dupefinder ul li").slideDown();
  	 window.jQuery("#scd_dupefinder img").on('error', function() { window.jQuery(this).css('display','none'); });
  	 document.getElementById("scd_dupefinder").getElementsByTagName('button')[0].innerHTML=this.state.btnText;
  }
  
  componentDidMount() {
  	//debugger;
  	this.state.isLoaded = false;
  	var params = new FormData();
		params.append('action', 'dupefinderList');
		params.append('start', this.state.start );
 		params.append('limit', this.state.limit );
 		params.append('post_id', scd_dupefinder_obj.post_id) ;
 		//console.log(params);
    axios.post (scd_dupefinder_obj.ajax_url, params).then(
    response => {
    	  //debugger;
    	  //console.log(response);
        this.setState({
        	btnText : "Load More...",
	        isLoaded: true,
	       	start: parseInt(this.state.start)+parseInt(this.state.limit),
	        posts: this.state.posts.concat(JSON.parse(response.data))
				});
    }, (error) => {
  			console.log(error);
  	});
  }
  
  handleSubmit(e) {
       e.preventDefault();  
       document.getElementById("scd_dupefinder").getElementsByTagName('button')[0].innerHTML='<span class="spinner-border spinner-border-sm"></span> Loading...';
       this.componentDidMount();
    }
    
  createMarkup(html) {
    return { __html: html };
  }
  render() {  	
  	if (this.state.isLoaded==false) {
  		return ( 
  			<div>
				<Spinner animation="border" variant="primary" role="status">
				  <span className="visually-hidden"></span>
				</Spinner><span style={{"font-size":"20px","font-weight":"bolder","vertical-align": "baseline"}}> Loading...</span>
				</div>
      );
  	} else {  		  
  		    return (
			      <ul>
			        {this.state.posts.map(post => (          
			           <li class="scd_post">
					   			<a href={post.permalink}>
					   				<div>{post.percentage}%<br/><span>match</span></div>
					   			</a>
					  	 		<div><a href={post.permalink}>{post.post_title}</a>			   
						   		<p>Also contains: {post.in_common.join(', ')} ... <a href={post.permalink}>See more</a></p></div>
						   		<a alt="alt" href={post.permalink}> <div dangerouslySetInnerHTML={this.createMarkup(post.image)} /></a>					   			
			         </li>
			        ))}
			        	<div style={{"clear":"both"}}></div>
			          <form onSubmit={this.handleSubmit}><button class="btn btn-primary">{this.state.btnText}</button></form>
			      </ul>
			    );
  		}
  }
}
export default PostList;