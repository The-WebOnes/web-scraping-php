const SEARCH_SERVICE = `http://localhost/projects/webbot/Server/services/queryService.php`;
const GET_LINKS_SERVICE = `http://localhost/projects/webbot/Server/services/getLinks.php`;
const STORE_SERVICE = `http://localhost/projects/webbot/Server/services/storeService.php`;

const SEARCH_INPUT = document.getElementById("SearchInput");
const SEARCH_BUTTON = document.getElementById("buttonSearch");
const CONTAINER_RESULTS = document.getElementById("results");
const ADD_LINK_BUTTON = document.getElementById("Addlink");
const LINKS_INPUT = document.getElementById("LinksInput");
const CONTAINER_FORM = document.getElementById("containerForm");
const SEND_LINKS_BUTTON = document.getElementById("SummitButton");

const getResponse = async (direction) => {
  try {
    let Search = SEARCH_INPUT.value;
    const response = await fetch(direction + `?q=${Search}`);
    let data = await response.text();
    //console.log(data);
    CONTAINER_RESULTS.innerHTML = data;
  } catch (error) {
    //console.log(error);
    CONTAINER_RESULTS.innerHTML = "ocurrio un error indesperado";
  }
};

SEARCH_BUTTON.addEventListener("click", () => {
  getResponse(SEARCH_SERVICE);
});

ADD_LINK_BUTTON.addEventListener("click", () => {
  CONTAINER_FORM.style.display = "flex";
  getLinks(GET_LINKS_SERVICE);
});

SEND_LINKS_BUTTON.addEventListener("click", () => {
  CONTAINER_FORM.style.display = "none";
  summitLinks(STORE_SERVICE);
});

const summitLinks = async (direction) => {
  try {
    let links = LINKS_INPUT.value;
    let lines = links.split('\n');
    
    const formData = new FormData();
    formData.append("links", JSON.stringify(lines));

    console.log(formData);

    const response = await fetch(`${direction}`, {
      method: "POST",
      body: formData,
    });

    let data = await response.text();
    console.log(data);
    LINKS_INPUT.value = "";
  } catch (error) {
    console.log(error);
  }
};

const getLinks = async (direction) => {
    try {
      let links = LINKS_INPUT.value;
  
      const formData = new FormData();
      formData.append("links", links);
  
      const response = await fetch(`${direction}`, {
        method: "POST",
        body: formData,
      });
  
      let data = await response.text();
      //console.log(data);
      LINKS_INPUT.value = data;
    } catch (error) {
      console.log(error);
    }
  };
