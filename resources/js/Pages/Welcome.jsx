import TextInput from "@/Components/TextInput.jsx";
import {useEffect, useState} from "react";
import PrimaryButton from "@/Components/PrimaryButton.jsx";

export default function Welcome() {
    const [lookupWord, setLookupWord] = useState("");
    const [searchResults, setSearchResults] = useState("");


    const fetchData = async (value) => {
        if (value.length <= 1) {
            return false;
        }
        try {
            const response = await axios.get(import.meta.env.VITE_APP_URL + value);
            setSearchResults(response.data);
        } catch (error) {
            console.error("Error fetching searchResults:", error);
        }
    }

    useEffect(() => {}, [lookupWord]);

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
                <div className="flex justify-end mt-5">
                    <PrimaryButton
                        className="bg-blue-600"
                        onClick={() => handleSubmit(lookupWord)}
                    > Translate
                    </PrimaryButton>
                </div>
                <div className="ml-3 mr-3 mt-5 grid grid-cols-1 gap-4 ">
                    {Object.keys(searchResults).map((category) => (
                        <div key={category} className="border border-white border-solid">
                            <p><strong>PART OF SPEECH</strong></p>
                            <h2>{category}</h2>
                            {Object.keys(searchResults[category]).map((verb) => (
                                <div key={verb} className="border border-white border-solid">
                                    <p><strong>DEFINITION</strong></p>
                                    <h3>{verb}</h3>
                                    {Object.keys(searchResults[category][verb]).map((translation) => (
                                        <div key={translation} className="border border-white border-solid">
                                            <p><strong>TRANSLATION</strong></p>
                                            <h4>{translation}</h4>
                                            <p><strong>USAGE EXAMPLES</strong></p>
                                            <p>Spanish: {searchResults[category][verb][translation].spanish}</p>
                                            <p>English: {searchResults[category][verb][translation].english}</p>
                                        </div>
                                    ))}
                                </div>
                            ))}
                        </div>
                    ))}
                </div>
            </div>
        </>
    );
}
