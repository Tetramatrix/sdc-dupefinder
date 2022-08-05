//import React, { Suspense } from "react";
//import React from 'react';
//import ReactDOM from 'react-dom';
//import { BrowserRouter as Router, Routes, Route, useLocation, Link } from 'react-router-dom';
import PostList from '../components/postList';

// Added lines to use wp.element instead of importing React
const { Component, render } = wp.element;

//const Shows = React.lazy(() => PostList);

// Render the app inside our shortcode's #app div
render(
		<PostList />,document.getElementById('scd_dupefinder')
);