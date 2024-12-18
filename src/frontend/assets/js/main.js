'use strict';
console.log('Main.js loaded');

import { initSlideshow } from './modules/slideshow.js';
import { initPagination } from './modules/pagination.js';
import { initializePortfolio } from './modules/portfolio.js';
import { initializeBlogs } from './modules/blogs.js';
import { initContact } from './modules/contact.js';
import { initNavigation } from './modules/navigation.js';
import { initTestimonials } from './modules/testimonials.js';
import { initCodeLineAdder } from './modules/codeLineAdder.js';
import { initToolsTable } from './modules/toolsTable.js';
import { initCollapsibleSections } from './modules/collapsibleSections.js';
import { initChat } from './modules/chat.js';
import { initInfiniteScroll } from './modules/infiniteScroll.js';
import { initCollapse } from './modules/collapse.js';
import { initBlogPagination } from './modules/blogPagination.js';
import FloatingChat from './modules/floatingChat.js';
import initCertificationSlider from './modules/certificationSlider.js';

document.addEventListener('DOMContentLoaded', function() {
    initSlideshow(document.querySelector('.slideshow-outer-container'));
    initPagination(document.querySelector('.blog-details-container'));
    initNavigation();
    initializePortfolio('portfolio-container');
    initializeBlogs('blog-container');
    initContact();
    initTestimonials();
    initCodeLineAdder();
    initToolsTable();
    initCollapsibleSections();
    initChat();
    initInfiniteScroll();
    initCollapse();
    initBlogPagination();
    FloatingChat.init();
    initCertificationSlider();
});