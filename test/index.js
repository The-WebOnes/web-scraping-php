const SEND_LINKS_SERVICE = "http://localhost/projects/webbot/test/crawler.php";
const GET_LINKS_SERVICE = "http://localhost/projects/webbot/test/crawler.php";

const ADD_LINK_BUTTON = document.getElementById("Addlink");
const LINKS_INPUT = document.getElementById("LinksInput");
const CONTAINER_FORM = document.getElementById("containerForm");
const SEND_LINKS_BUTTON = document.getElementById("SummitButton");

ADD_LINK_BUTTON.addEventListener("click", () => {
  CONTAINER_FORM.style.display = "flex";
});

SEND_LINKS_BUTTON.addEventListener("click", () => {
  CONTAINER_FORM.style.display = "none";
  summitLinks(LINKS_SERVICE);
});

const summitLinks = async (direction) => {
  try {
    let links = LINKS_INPUT.value;
    let lines = links.split('\n');
    alert(lines);
    const formData = new FormData();
    formData.append("links", lines);

    const response = await fetch(`${direction}`, {
      method: "POST",
      body: formData,
    });

    let data = await response.text();
    //console.log(data);
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
      LINKS_INPUT.value = "";
    } catch (error) {
      console.log(error);
    }
  };