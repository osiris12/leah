import TextInput from "@/Components/TextInput.jsx";
import {useEffect, useState} from "react";
import PrimaryButton from "@/Components/PrimaryButton.jsx";

export default function Welcome() {
    const [lookupWord, setLookupWord] = useState("");
    const [searchResults, setSearchResults] = useState("");
    

    const fetchData = (value) => {
        if(value.length <= 1) {
            return false;
        }
        fetch(import.meta.env.VITE_APP_URL + value)
            .then((response) => response.json())
            .then((json) => {
                setSearchResults(json);
            });
    }

    useEffect(() => {
    }, [lookupWord]);

    const handleSubmit = (lookupWord) => {
        fetchData(lookupWord);
    }

    return (
        <>
            <div className="w-full mb-48"></div>
            <div>
                <div className="flex h justify-end">
                    <TextInput
                        className="text-2xl bg-gray-800"
                        placeholder="search"
                        onChange={(e) => setLookupWord(e.target.value)}
                    />
                </div>
                <div className="flex justify-end mt-2">
                    <PrimaryButton
                        className="bg-blue-600"
                        onClick={(e) => handleSubmit(lookupWord)}
                    > Translate
                    </PrimaryButton>
                </div>
                <div className=" mt-3 bg-gray-600">
                    {console.log(searchResults)}
                    {Object.entries(searchResults).map(([key, value]) => {
                        return (
                            <div key={key}>
                                <p>{key}</p>
                            </div>
                        );
                    })}
                </div>
            </div>
        </>
    );
}
